<?php
declare(strict_types=1);

namespace Test\Patrones\Structural\Bridge;

class PlainTextFormatter implements Formatter
{
    public function format(string $text): string
    {
        return $text;
    }
}
