<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * =============================================
 * NullObject Tests
 * =============================================
 */
class NullObjectTest extends TestCase
{
    public function testNullLoggerDoesNotOutputAnything(): void
    {
        $logger = new \DesignPatterns\Behavioral\NullObject\NullLogger();

        $this->expectOutputString('');
        $logger->log('test message');
    }

    public function testPrintLoggerOutputsMessage(): void
    {
        $logger = new \DesignPatterns\Behavioral\NullObject\PrintLogger();

        $this->expectOutputString('test message');
        $logger->log('test message');
    }

    public function testServiceWithNullLogger(): void
    {
        $logger = new \DesignPatterns\Behavioral\NullObject\NullLogger();
        $service = new \DesignPatterns\Behavioral\NullObject\Service($logger);

        $this->expectOutputString('');
        $service->doSomething();
    }

    public function testServiceWithPrintLogger(): void
    {
        $logger = new \DesignPatterns\Behavioral\NullObject\PrintLogger();
        $service = new \DesignPatterns\Behavioral\NullObject\Service($logger);

        $this->expectOutputRegex('/We are in/');
        $service->doSomething();
    }

    public function testBothLoggersImplementInterface(): void
    {
        $nullLogger = new \DesignPatterns\Behavioral\NullObject\NullLogger();
        $printLogger = new \DesignPatterns\Behavioral\NullObject\PrintLogger();

        $this->assertInstanceOf(\DesignPatterns\Behavioral\NullObject\Logger::class, $nullLogger);
        $this->assertInstanceOf(\DesignPatterns\Behavioral\NullObject\Logger::class, $printLogger);
    }
}

/**
 * =============================================
 * Specification Tests
 * =============================================
 */
class SpecificationTest extends TestCase
{
    public function testPriceSpecificationWithinRange(): void
    {
        $spec = new \DesignPatterns\Behavioral\Specification\PriceSpecification(10.0, 50.0);
        $item = new \DesignPatterns\Behavioral\Specification\Item(25.0);

        $this->assertTrue($spec->isSatisfiedBy($item));
    }

    public function testPriceSpecificationAboveMax(): void
    {
        $spec = new \DesignPatterns\Behavioral\Specification\PriceSpecification(10.0, 50.0);
        $item = new \DesignPatterns\Behavioral\Specification\Item(60.0);

        $this->assertFalse($spec->isSatisfiedBy($item));
    }

    public function testPriceSpecificationBelowMin(): void
    {
        $spec = new \DesignPatterns\Behavioral\Specification\PriceSpecification(10.0, 50.0);
        $item = new \DesignPatterns\Behavioral\Specification\Item(5.0);

        $this->assertFalse($spec->isSatisfiedBy($item));
    }

    public function testPriceSpecificationWithNullMin(): void
    {
        $spec = new \DesignPatterns\Behavioral\Specification\PriceSpecification(null, 50.0);
        $item = new \DesignPatterns\Behavioral\Specification\Item(1.0);

        $this->assertTrue($spec->isSatisfiedBy($item));
    }

    public function testPriceSpecificationWithNullMax(): void
    {
        $spec = new \DesignPatterns\Behavioral\Specification\PriceSpecification(10.0, null);
        $item = new \DesignPatterns\Behavioral\Specification\Item(999.0);

        $this->assertTrue($spec->isSatisfiedBy($item));
    }

    public function testAndSpecification(): void
    {
        $cheap = new \DesignPatterns\Behavioral\Specification\PriceSpecification(null, 50.0);
        $expensive = new \DesignPatterns\Behavioral\Specification\PriceSpecification(20.0, null);
        $andSpec = new \DesignPatterns\Behavioral\Specification\AndSpecification($cheap, $expensive);

        $item30 = new \DesignPatterns\Behavioral\Specification\Item(30.0);
        $item10 = new \DesignPatterns\Behavioral\Specification\Item(10.0);
        $item60 = new \DesignPatterns\Behavioral\Specification\Item(60.0);

        $this->assertTrue($andSpec->isSatisfiedBy($item30));  // 20-50
        $this->assertFalse($andSpec->isSatisfiedBy($item10)); // below 20
        $this->assertFalse($andSpec->isSatisfiedBy($item60)); // above 50
    }

    public function testOrSpecification(): void
    {
        $cheap = new \DesignPatterns\Behavioral\Specification\PriceSpecification(null, 10.0);
        $expensive = new \DesignPatterns\Behavioral\Specification\PriceSpecification(90.0, null);
        $orSpec = new \DesignPatterns\Behavioral\Specification\OrSpecification($cheap, $expensive);

        $item5 = new \DesignPatterns\Behavioral\Specification\Item(5.0);
        $item100 = new \DesignPatterns\Behavioral\Specification\Item(100.0);
        $item50 = new \DesignPatterns\Behavioral\Specification\Item(50.0);

        $this->assertTrue($orSpec->isSatisfiedBy($item5));
        $this->assertTrue($orSpec->isSatisfiedBy($item100));
        $this->assertFalse($orSpec->isSatisfiedBy($item50));
    }

    public function testNotSpecification(): void
    {
        $cheap = new \DesignPatterns\Behavioral\Specification\PriceSpecification(null, 50.0);
        $notCheap = new \DesignPatterns\Behavioral\Specification\NotSpecification($cheap);

        $item30 = new \DesignPatterns\Behavioral\Specification\Item(30.0);
        $item60 = new \DesignPatterns\Behavioral\Specification\Item(60.0);

        $this->assertFalse($notCheap->isSatisfiedBy($item30));
        $this->assertTrue($notCheap->isSatisfiedBy($item60));
    }

    public function testItemGetPrice(): void
    {
        $item = new \DesignPatterns\Behavioral\Specification\Item(42.5);
        $this->assertSame(42.5, $item->getPrice());
    }
}

/**
 * =============================================
 * EAV (Entity-Attribute-Value) Tests
 * =============================================
 */
class EAVTest extends TestCase
{
    public function testAttributeHasName(): void
    {
        $color = new \DesignPatterns\More\EAV\Attribute('color');
        $this->assertSame('color', (string) $color);
    }

    public function testValueRegistersWithAttribute(): void
    {
        $color = new \DesignPatterns\More\EAV\Attribute('color');
        $red = new \DesignPatterns\More\EAV\Value($color, 'red');

        $this->assertCount(1, $color->getValues());
        $this->assertSame('color: red', (string) $red);
    }

    public function testAttributeCanHaveMultipleValues(): void
    {
        $size = new \DesignPatterns\More\EAV\Attribute('size');
        new \DesignPatterns\More\EAV\Value($size, 'small');
        new \DesignPatterns\More\EAV\Value($size, 'medium');
        new \DesignPatterns\More\EAV\Value($size, 'large');

        $this->assertCount(3, $size->getValues());
    }

    public function testEntityToString(): void
    {
        $color = new \DesignPatterns\More\EAV\Attribute('color');
        $red = new \DesignPatterns\More\EAV\Value($color, 'red');

        $entity = new \DesignPatterns\More\EAV\Entity('T-Shirt', [$red]);
        $str = (string) $entity;

        $this->assertStringContainsString('T-Shirt', $str);
        $this->assertStringContainsString('color: red', $str);
    }

    public function testEntityWithMultipleValues(): void
    {
        $color = new \DesignPatterns\More\EAV\Attribute('color');
        $size = new \DesignPatterns\More\EAV\Attribute('size');

        $red = new \DesignPatterns\More\EAV\Value($color, 'red');
        $large = new \DesignPatterns\More\EAV\Value($size, 'large');

        $entity = new \DesignPatterns\More\EAV\Entity('Hoodie', [$red, $large]);
        $str = (string) $entity;

        $this->assertStringContainsString('Hoodie', $str);
        $this->assertStringContainsString('color: red', $str);
        $this->assertStringContainsString('size: large', $str);
    }
}

/**
 * =============================================
 * Repository Tests
 * =============================================
 */
class RepositoryTest extends TestCase
{
    private \DesignPatterns\More\Repository\PostRepository $repository;

    protected function setUp(): void
    {
        $persistence = new \DesignPatterns\More\Repository\InMemoryPersistence();
        $this->repository = new \DesignPatterns\More\Repository\PostRepository($persistence);
    }

    public function testCanGenerateId(): void
    {
        $id = $this->repository->generateId();

        $this->assertInstanceOf(\DesignPatterns\More\Repository\Domain\PostId::class, $id);
        $this->assertSame(1, $id->toInt());
    }

    public function testCanSaveAndFindPost(): void
    {
        $id = $this->repository->generateId();
        $post = \DesignPatterns\More\Repository\Domain\Post::draft($id, 'My Title', 'My Content');
        $this->repository->save($post);

        $found = $this->repository->findById($id);

        $this->assertSame('My Title', $found->getTitle());
        $this->assertSame('My Content', $found->getText());
        $this->assertSame('draft', $found->getStatus()->toString());
    }

    public function testFindByIdThrowsForNonExistent(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->repository->findById(\DesignPatterns\More\Repository\Domain\PostId::fromInt(999));
    }

    public function testPostIdThrowsForInvalidId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        \DesignPatterns\More\Repository\Domain\PostId::fromInt(0);
    }

    public function testPostIdThrowsForNegativeId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        \DesignPatterns\More\Repository\Domain\PostId::fromInt(-5);
    }

    public function testPostStatusFromString(): void
    {
        $draft = \DesignPatterns\More\Repository\Domain\PostStatus::fromString('draft');
        $this->assertSame(1, $draft->toInt());
        $this->assertSame('draft', $draft->toString());

        $published = \DesignPatterns\More\Repository\Domain\PostStatus::fromString('published');
        $this->assertSame(2, $published->toInt());
        $this->assertSame('published', $published->toString());
    }

    public function testPostStatusFromInt(): void
    {
        $draft = \DesignPatterns\More\Repository\Domain\PostStatus::fromInt(1);
        $this->assertSame('draft', $draft->toString());

        $published = \DesignPatterns\More\Repository\Domain\PostStatus::fromInt(2);
        $this->assertSame('published', $published->toString());
    }

    public function testPostStatusThrowsForInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        \DesignPatterns\More\Repository\Domain\PostStatus::fromString('invalid');
    }

    public function testPostStatusThrowsForInvalidInt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        \DesignPatterns\More\Repository\Domain\PostStatus::fromInt(99);
    }

    public function testInMemoryPersistenceDeleteThrowsForMissingId(): void
    {
        $persistence = new \DesignPatterns\More\Repository\InMemoryPersistence();

        $this->expectException(OutOfBoundsException::class);
        $persistence->delete(1);
    }

    public function testInMemoryPersistenceRetrieveThrowsForMissingId(): void
    {
        $persistence = new \DesignPatterns\More\Repository\InMemoryPersistence();

        $this->expectException(OutOfBoundsException::class);
        $persistence->retrieve(1);
    }
}
