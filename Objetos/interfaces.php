<?php
interface PaymentGateway
{
    public function charge($amont);
}

class PurchaseController
{
    PaymentGateway $paymentGageway;

    public function __construct(PaymentGateway $paymentGageway)
    {
        $this->paymentGageway = $paymentGageway;
    }
    public function store()
    {
        $this->paymentGageway->charge(100);
    }
}

class Paypal implements PaymentGateway
{
    public function charge($amount)
    {
        echo "Charging {$amount} with paypal ";
    }
}

class Stripe implements PaymentGateway
{
    public function charge($amount)
    {
        echo "Charging {$amount} with stripe ";
    }
}

$paypal = new Paypal();
(new PurchaseController($paypal)->store());