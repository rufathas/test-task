<?php

namespace App\Payments;

use App\Enum\PaymentProcessor;
use LogicException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class PaymentFactory
{

    /** @param iterable<PaymentService> $strategies */
    public function __construct(
        #[TaggedIterator('app.payment_strategy')]
        private readonly iterable $strategies
    ) {}

    public function getPaymentService(PaymentProcessor $paymentProcessor): PaymentService
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($paymentProcessor)) {
                return $strategy;
            }
        }
        throw new LogicException(sprintf('Unsupported payment provider "%s"', $paymentProcessor->value));
    }
}
