<?php
# usaremos la clase abstracta cuando no querámos instanciarla
# implementar ciertos métodos no cambiaran como getImage y también otros quieran heredarse como el método eat a sus clases hijas
abstract class Fruit
{
    public function getImage()
    {
      // apple.png , orange.png
      echo strtolower((new ReflectionClass($this))->getShortName()) . '.png' . "<br>";
    }

    abstract public function eat();
}

class Apple extends Fruit
{
    public function eat()
    {
        echo "eating apple ... ";
    }  
}

class Orange extends Fruit
{
    public function eat()
    {
        echo "eating orange ... ";
    }
}

class Banana extends Fruit
{
    public function eat()
    {
        echo "eating banana ... ";
    }
}

$manzana = new Apple();
$manzana->getImage();
$manzana->eat();