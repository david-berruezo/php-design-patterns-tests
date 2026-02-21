<?php
Interface MueblesFactory
{
    public function crearSilla():Silla;
    public function crearMesa():Mesa;
    public function crearSofa():Sofa;
}

class VictorianaMueblesFactory implements MueblesFactory
{
    public function crearSilla():Silla
    {
        return new VictorianaSilla();
    }
    public function crearMesa():Mesa
    {
        return new VictorianaMesa();
    }
    public function crearSofa():Sofa
    {
        return new VictorianaSofa();
    }
}

class ModernaMueblesFactory implements MueblesFactory
{
    public function crearSilla():Silla
    {
        return new ModernaSilla();
    }
    public function crearMesa():Mesa
    {
        return new ModernaMesa();
    }
    public function crearSofa():Sofa
    {
        return new ModernaSofa();
    }
}


class ArtDecoMueblesFactory implements MueblesFactory
{
    public function crearSilla():Silla
    {
        return new ArtDecoSilla();
    }
    public function crearMesa():Mesa
    {
        return new ArtDecoMesa();
    }
    public function crearSofa():Sofa
    {
        return new ArtDecoSofa();
    }
}


Interface Silla
{
    public function usefulFunctionSilla(): string;
    public function anotherUsefulFunctionMesa(Mesa $collaborator): string;
}

Interface Sofa{
    public function usefulFunctionSofa(): string;
}

Interface Mesa{
    public function usefulFunctionMesa(): string;
}

# Silla
class VictorianaSilla implements Silla
{
    public function usefulFunctionSilla(): string
    {
        return "The result of the product silla victoriana.";
    }

    public function anotherUsefulFunctionMesa(Mesa $collaborator): string
    {
        $result = $collaborator->usefulFunctionMesa();
        return "The result of the product silla victoriana colaborator {$result}";
    }
}

class ModernaSilla implements Silla
{
    public function usefulFunctionSilla(): string
    {
        return "The result of the product silla moderna.";
    }

    public function anotherUsefulFunctionMesa(Mesa $collaborator): string
    {
        return "The result of the product silla moderna. colaborator {$collaborator}";
    }
}

class ArtDecoSilla implements Silla
{
    public function usefulFunctionSilla(): string
    {
        return "The result of the product silla art deco.";
    }

    public function anotherUsefulFunctionMesa(Mesa $collaborator): string
    {
        return "The result of the product silla art deco. colaborator {$collaborator}";
    }
}

# Sofa
class VictorianaSofa implements Sofa
{
    public function usefulFunctionSofa(): string
    {
        return "The result of the product sofa victoriana.";
    }
}

class ModernaSofa implements Sofa
{
    public function usefulFunctionSofa(): string
    {
        return "The result of the product sofa moderna.";
    }
}

class ArtDecoSofa implements Sofa
{
    public function usefulFunctionSofa(): string
    {
        return "The result of the product sofa art deco.";
    }
}

# Mesa
class VictorianaMesa implements Mesa
{
    public function usefulFunctionMesa(): string
    {
        return "The result of the product mesa victoriana.";
    }
}

class ModernaMesa implements Mesa
{
    public function usefulFunctionMesa(): string
    {
        return "The result of the product mesa moderna.";
    }
}

class ArtDecoMesa implements Mesa
{
    public function usefulFunctionMesa(): string
    {
        return "The result of the product mesa art deco.";
    }
}


function clientCode(MueblesFactory $factory)
{

    # creamos la silla y la mesa Victoriana
    $sillaVictoriana = $factory->crearSilla();
    $mesaVictoriana = $factory->crearMesa();

    echo $sillaVictoriana->usefulFunctionSilla();
    echo $mesaVictoriana->usefulFunctionMesa();

    # Luego enlazamos la silla con la mesa Victoriana
    echo $sillaVictoriana->anotherUsefulFunctionMesa($mesaVictoriana);

    //$productA = $factory->createProductA();
    //$productB = $factory->createProductB();
    //echo $productB->usefulFunctionB() . "<br>";
    //echo $productB->anotherUsefulFunctionB($productA) . "<br>";
}


# enviamos una factoria Victoriana de muebles
clientCode(new VictorianaMueblesFactory());
