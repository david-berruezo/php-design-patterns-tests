<?php
namespace RefactoringGuru\FactoryMethod\Ejemplo;

interface Logistics{
    public function planDelivery(): Product;
    public function createTransport(): Product;
}

class RoadLogistics implements Logistics{
    public function __construct()
    {
        return $this;
    }
    public function planDelivery(): Product
    {
        $truck = new Truck();
        return $truck;
    }
    public function createTransport(): Product
    {
        $truck = new Truck();
        return $truck;
    }
}

class SeaLogistics implements Logistics{
    public function __construct()
    {
        return $this;
    }
    public function planDelivery(): Product
    {
        $ship = new Ship();
        return $ship;
    }
    public function createTransport(): Product
    {
        $ship = new Ship();
        return $ship;
    }
}

class Product{
    protected $name;
    protected $license;
}

class Ship extends Product{
    public function __construct()
    {
        echo "creamos un nuevo Ship";
    }
}

class Truck extends Product{
    public function __construct()
    {
        echo "creamos un nuevo Truck";
    }
}


function clientCode(Logistics $logistics){
    $logistics->createTransport();
}

# llamamos a una función para crear el un tipo de Logistica
clientCode(new RoadLogistics());
clientCode(new SeaLogistics());



