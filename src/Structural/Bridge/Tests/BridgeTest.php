<?php
declare(strict_types=1);

namespace Test\Patrones\Structural\Bridge\Tests;

use Test\Patrones\Structural\Bridge\HelloWorldService;
use Test\Patrones\Structural\Bridge\HtmlFormatter;
use Test\Patrones\Structural\Bridge\PlainTextFormatter;

use PHPUnit\Framework\TestCase;

class BridgeTest extends TestCase
{
    public function testCanPrintUsingThePlainTextFormatter()
    {
        $service = new HelloWorldService(new PlainTextFormatter());

        $this->assertSame('Hello World', $service->get());
    }

    public function testCanPrintUsingTheHtmlFormatter()
    {
        $service = new HelloWorldService(new HtmlFormatter());

        $this->assertSame('<p>Hello World</p>', $service->get());
    }
}
