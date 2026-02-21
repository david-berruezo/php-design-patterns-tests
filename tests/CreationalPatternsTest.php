<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use DesignPatterns\Creational\SimpleFactory\SimpleFactory;
use DesignPatterns\Creational\SimpleFactory\Bicycle;
use DesignPatterns\Creational\StaticFactory\StaticFactory;
use DesignPatterns\Creational\StaticFactory\FormatNumber;
use DesignPatterns\Creational\StaticFactory\FormatString;
use DesignPatterns\Creational\StaticFactory\Formatter;
use DesignPatterns\Creational\Pool\WorkerPool;
use DesignPatterns\Creational\Pool\StringReverseWorker;

/**
 * =============================================
 * SimpleFactory Tests
 * =============================================
 */
class SimpleFactoryTest extends TestCase
{
    public function testCanCreateBicycle(): void
    {
        $factory = new SimpleFactory();
        $bicycle = $factory->createBicycle();

        $this->assertInstanceOf(Bicycle::class, $bicycle);
    }

    public function testBicycleHasDriveToMethod(): void
    {
        $bicycle = new Bicycle();
        // driveTo returns void, just ensure it doesn't throw
        $bicycle->driveTo('Barcelona');
        $this->assertTrue(true);
    }
}

/**
 * =============================================
 * StaticFactory Tests
 * =============================================
 */
class StaticFactoryTest extends TestCase
{
    public function testCanCreateNumberFormatter(): void
    {
        $formatter = StaticFactory::factory('number');

        $this->assertInstanceOf(FormatNumber::class, $formatter);
        $this->assertInstanceOf(Formatter::class, $formatter);
    }

    public function testCanCreateStringFormatter(): void
    {
        $formatter = StaticFactory::factory('string');

        $this->assertInstanceOf(FormatString::class, $formatter);
        $this->assertInstanceOf(Formatter::class, $formatter);
    }

    public function testThrowsExceptionForUnknownType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        StaticFactory::factory('unknown');
    }

    public function testNumberFormatterFormatsCorrectly(): void
    {
        $formatter = StaticFactory::factory('number');

        $this->assertSame('1,234', $formatter->format('1234'));
    }

    public function testStringFormatterReturnsInput(): void
    {
        $formatter = StaticFactory::factory('string');

        $this->assertSame('hello', $formatter->format('hello'));
    }
}

/**
 * =============================================
 * Pool Tests
 * =============================================
 */
class PoolTest extends TestCase
{
    public function testCanGetWorkerFromPool(): void
    {
        $pool = new WorkerPool();
        $worker = $pool->get();

        $this->assertInstanceOf(StringReverseWorker::class, $worker);
    }

    public function testPoolCountIncrementsWhenGettingWorker(): void
    {
        $pool = new WorkerPool();
        $this->assertCount(0, $pool);

        $pool->get();
        $this->assertCount(1, $pool);

        $pool->get();
        $this->assertCount(2, $pool);
    }

    public function testWorkerCanReverseString(): void
    {
        $pool = new WorkerPool();
        $worker = $pool->get();

        $this->assertSame('olleH', $worker->run('Hello'));
    }

    public function testDisposedWorkerIsReused(): void
    {
        $pool = new WorkerPool();

        $worker1 = $pool->get();
        $this->assertCount(1, $pool);

        $pool->dispose($worker1);
        $this->assertCount(1, $pool);

        $worker2 = $pool->get();
        $this->assertCount(1, $pool);
        $this->assertSame($worker1, $worker2);
    }

    public function testDisposedWorkerAndNewWorker(): void
    {
        $pool = new WorkerPool();

        $worker1 = $pool->get();
        $worker2 = $pool->get();
        $this->assertCount(2, $pool);

        $pool->dispose($worker1);
        $this->assertCount(2, $pool);

        $worker3 = $pool->get();
        $this->assertSame($worker1, $worker3);
        $this->assertCount(2, $pool);
    }
}
