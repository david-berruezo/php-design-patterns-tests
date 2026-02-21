<?php
class price
{
    const MXN = 'MXN';
    const USD = 'USD';

    private float $amount;
    private string $currency;

    public function __construct(float $amount , string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function sum(Price $price):self
    {
        if (!$this->hasSameCurrency($price))
        {
            throw new \Exception("Prices with different currencies cannot be sum");
        }
        return  new self($this->amount + $price->amount , $this->currency);
    }

    private function hasSameCurrency(Price $price):bool
    {
        return $this->currency === $price->currency;
    }

}

$price1 = new Price(100 , "MXN");
$price2 = new Price(5 , "USD");
$price3 = $price1->sum($price2);