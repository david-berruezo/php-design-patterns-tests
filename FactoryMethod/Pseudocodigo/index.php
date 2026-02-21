<?php
namespace RefactoringGuru\FactoryMethod\Pseudocodigo;

abstract class Dialog{
    abstract function createButton():Button;
    public function render(): Button{}
}

interface Button{
    public function render();
    public function onClick();
}

class WindowsDialog extends Dialog{
    public function createButton():Button
    {
        return new WindowsButton();
    }

    public function render(): Button
    {
        return new WindowsButton();
    }
}

class WebDialog extends Dialog{
    public function createButton():Button
    {
        return new HTMLButton();
    }
    public function render(): Button
    {
        return new HTMLButton();
    }
}

class WindowsButton implements Button
{
    public function __construct()
    {
        echo "creamos un nuevo Windows Button<br>";
    }

    public function render(): Button
    {

    }

    public function onCLick(): Button
    {

    }

}


class HTMLButton implements Button
{
    public function __construct()
    {
        echo "creamos un nuevo HTML Button<br>";
    }
    public function render(): Button
    {

    }

    public function onCLick(): Button
    {

    }
}


function client(Dialog $dialog)
{
    $dialog->createButton();
}

# lanzamos tipos de botones dialogo
$webDialog = client(new WebDialog());

# lanzamos tipos de botones dialogo
$windowDialog = client(new WindowsDialog());



