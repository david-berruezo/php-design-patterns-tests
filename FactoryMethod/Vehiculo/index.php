<?php
namespace RefactoringGuru\FactoryMethod\Vehiculo;

interface Motocicleta
{
    public function crearMotocicleta():Moto;
}

class MotoCarreras implements Motocicleta{
    public function crearMotocicleta(): Moto{
        return new HondaCBR();
    }
}

class MotoScooter implements Motocicleta{
    public function crearMotocicleta(): Moto{
        return new HondaScoopy();
    }
}

interface Moto{
    public function arrancar();
}

class HondaCBR implements Moto{
    public function __construct()
    {
        echo "creado Honda CBR<br>";
    }
    public function arrancar(){
        echo "wrong wrong<br>";
    }
}

class HondaScoopy implements Moto{
    public function __construct()
    {
        echo "creado Honda Scoopy<br>";
    }
    public function arrancar(){
        echo "rum rum<br>";
    }
}



interface Automovil
{
    public function crearAutomovil():Coche;
}


class CocheCarreras implements Automovil{
    public function crearAutomovil(): Coche{
        return new PorscheCarrera();
    }
}

class CocheFamiliar implements Automovil{
    public function crearAutomovil(): Coche{
        return new RenaultLaguna();
    }
}

interface Coche{
    public function arrancar();
}

class PorscheCarrera implements Coche{
    public function __construct()
    {
        echo "creado Porsche<br>";
    }
    public function arrancar(){
        echo "wrong wrong<br>";
    }
}

class RenaultLaguna implements Coche{
    public function __construct()
    {
        echo "creado Renault<br>";
    }
    public function arrancar(){
        echo "rum rum<br>";
    }
}

function clientAutomovil(Automovil $automovil)
{
    # creamos y arrancamos coche
    $coche = $automovil->crearAutomovil();
    $coche->arrancar();

}

function clientMoto(Motocicleta $motocicleta)
{
    # creamos y arrancamos moto
    $motocicleta = $motocicleta->crearMotocicleta();
    $motocicleta->arrancar();
}


# lanzamos tipos de coche carreras
$cocheCarreras = clientAutomovil(new CocheCarreras());
# lanzamos tipos de coche familiares
$cocheFamiliar = clientAutomovil(new CocheFamiliar());

# lanzamos tipos de motos carreras
$motoCarreras = clientMoto(new MotoCarreras());
# lanzamos tipos de motos scooter
$motoScooter = clientMoto(new MotoScooter());
