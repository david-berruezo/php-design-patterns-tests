<?php
class Sale
{
    private PaymenGateway $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function create()
    {
        // empty
    }

    public function cancel()
    {
        // get customer
        $this->paymentGateway->getCustomer();
        // get transaction
        $this->paymentGateway->getCustomerTransaction();
    }

    public function invoice()
    {
        // empty
    }

}

interface PaymentGateway
{
    public function getCustomer();
    public function getCustomerTransaction();
}


class Paypal implements PaymentGateway
{
    public function getCustomer()
    {
        
    }

    public function getCustomerTransaction()
    {

    }
}

class Stripe implements PaymentGateway
{
    public function getCustomer()
    {

    }

    public function getCustomerTransaction()
    {

    }
}

$venta_uno = new Sale(new Stripe())
$venta_dos = new Sale(new Paypal());