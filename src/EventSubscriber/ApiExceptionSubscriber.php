<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Enum\ExceptionEnum;
use App\Exception\CustomException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ParameterBagInterface $params,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['onException', -100]];
    }

    public function onException(ExceptionEvent $event): void
    {
        $req = $event->getRequest();
        $e = $event->getThrowable();

        $exceptionResponse = [
            'success' => false,
            'error' => [],
        ];


        if ($e instanceof UnprocessableEntityHttpException &&
            ($prev = $e->getPrevious()) instanceof ValidationFailedException
        ) {
            $violationsArr = [];
            /** @var ConstraintViolationInterface $v */
            foreach ($prev->getViolations() as $v) {
                $violationsArr[] = [
                    'field'   => $this->normalizePath($v->getPropertyPath()),
                    'message' => $v->getMessage(),
                ];
            }

            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
            $exceptionResponse['error']['messageEnum'] = ExceptionEnum::UNPROCESSABLE_ENTITY->name;
            $exceptionResponse['error']['messageText'] = ExceptionEnum::UNPROCESSABLE_ENTITY->value;
            $exceptionResponse['error']['details'] = $violationsArr;

        } else if ($e instanceof CustomException) {
            $status = $e->getCode();
            $exceptionResponse['error']['messageEnum'] = $e->getException()->name;
            $exceptionResponse['error']['messageText'] = $e->getException()->value;

        } else {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            if ($this->params->get('kernel.debug')) {
                $exceptionResponse['error']['messageEnum'] = ExceptionEnum::INTERNAL_SERVER_ERROR->name;
                $exceptionResponse['error']['messageText'] = $e->getMessage();

                $exceptionResponse['trace'] = array_map(
                    fn($t) => sprintf('%s:%d', $t['file'] ?? 'n/a', $t['line'] ?? 0),
                    array_slice($e->getTrace(), 0, 20)
                );
            } else {
                $exceptionResponse['error']['messageEnum'] = ExceptionEnum::INTERNAL_SERVER_ERROR->name;
                $exceptionResponse['error']['messageText'] = ExceptionEnum::INTERNAL_SERVER_ERROR->value;
            }
        }

        if ($this->params->get('kernel.debug')) {
            $exceptionResponse['trace'] = array_map(
                fn($t) => sprintf('%s:%d', $t['file'] ?? 'n/a', $t['line'] ?? 0),
                array_slice($e->getTrace(), 0, 20)
            );
        }

        if ($status >= 500) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
        } elseif ($status >= 400) {
            $this->logger->warning($e->getMessage(), ['exception' => $e, 'request' => $req]);
        }

        $response = new JsonResponse($exceptionResponse, $status);
        $response->headers->set('Content-Type', 'application/problem+json');

        $event->setResponse($response);
    }

    private function normalizePath(string $path): string
    {
        $path = preg_replace('/\[(\w+)\]/', '.$1', $path);
        return ltrim((string)$path, '.');
    }
}
