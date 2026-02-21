<?php
# impresora solo imprime
class Printer
{
    public function print()
    {
        echo "Printing ...";
    }

}

# scaner escanea y imprime
class ScannerPrinter extends Printer
{

    public function scan()
    {
        echo "Scanning ...";
    }
}

$printer = new ScannerPrinter();
$printer->scan();