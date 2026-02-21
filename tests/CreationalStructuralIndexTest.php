<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * =============================================
 * Abstract Factory - Conceptual Tests
 * =============================================
 */
class AbstractFactoryConceptualTest extends TestCase
{
    public function testConcreteFactory1CreatesProductA1(): void
    {
        $factory = new \RefactoringGuru\AbstractFactory\Conceptual\ConcreteFactory1();
        $productA = $factory->createProductA();

        $this->assertInstanceOf(
            \RefactoringGuru\AbstractFactory\Conceptual\AbstractProductA::class,
            $productA
        );
        $this->assertSame('The result of the product A1.', $productA->usefulFunctionA());
    }

    public function testConcreteFactory1CreatesProductB1(): void
    {
        $factory = new \RefactoringGuru\AbstractFactory\Conceptual\ConcreteFactory1();
        $productB = $factory->createProductB();

        $this->assertSame('The result of the product B1.', $productB->usefulFunctionB());
    }

    public function testConcreteFactory2CreatesProductA2(): void
    {
        $factory = new \RefactoringGuru\AbstractFactory\Conceptual\ConcreteFactory2();
        $productA = $factory->createProductA();

        $this->assertSame('The result of the product A2.', $productA->usefulFunctionA());
    }

    public function testConcreteFactory2CreatesProductB2(): void
    {
        $factory = new \RefactoringGuru\AbstractFactory\Conceptual\ConcreteFactory2();
        $productB = $factory->createProductB();

        $this->assertSame('The result of the product B2.', $productB->usefulFunctionB());
    }

    public function testProductB1CollaboratesWithProductA1(): void
    {
        $factory = new \RefactoringGuru\AbstractFactory\Conceptual\ConcreteFactory1();
        $productA = $factory->createProductA();
        $productB = $factory->createProductB();

        $result = $productB->anotherUsefulFunctionB($productA);

        $this->assertStringContainsString('B1 collaborating', $result);
        $this->assertStringContainsString('product A1', $result);
    }

    public function testProductB2CollaboratesWithProductA2(): void
    {
        $factory = new \RefactoringGuru\AbstractFactory\Conceptual\ConcreteFactory2();
        $productA = $factory->createProductA();
        $productB = $factory->createProductB();

        $result = $productB->anotherUsefulFunctionB($productA);

        $this->assertStringContainsString('B2 collaborating', $result);
        $this->assertStringContainsString('product A2', $result);
    }
}

/**
 * =============================================
 * Adapter - Conceptual Tests
 * =============================================
 */
class AdapterConceptualTest extends TestCase
{
    public function testTargetRequest(): void
    {
        $target = new \RefactoringGuru\Adapter\Target\Target();

        $this->assertSame("Target: The default target's behavior.", $target->request());
    }

    public function testAdapteeSpecificRequest(): void
    {
        $adaptee = new \RefactoringGuru\Adapter\Target\Adaptee();

        $this->assertSame('.eetpadA eht fo roivaheb laicepS', $adaptee->specificRequest());
    }

    public function testAdapterTranslatesRequest(): void
    {
        $adaptee = new \RefactoringGuru\Adapter\Target\Adaptee();
        $adapter = new \RefactoringGuru\Adapter\Target\Adapter($adaptee);

        $result = $adapter->request();

        $this->assertStringContainsString('TRANSLATED', $result);
        $this->assertStringContainsString('Special behavior of the Adaptee.', $result);
    }

    public function testAdapterExtendsTarget(): void
    {
        $adaptee = new \RefactoringGuru\Adapter\Target\Adaptee();
        $adapter = new \RefactoringGuru\Adapter\Target\Adapter($adaptee);

        $this->assertInstanceOf(\RefactoringGuru\Adapter\Target\Target::class, $adapter);
    }
}

/**
 * =============================================
 * Bridge - Conceptual Tests
 * =============================================
 */
class BridgeConceptualTest extends TestCase
{
    public function testAbstractionWithImplementationA(): void
    {
        $impl = new \RefactoringGuru\Bridge\Conceptual\ConcreteImplementationA();
        $abstraction = new \RefactoringGuru\Bridge\Conceptual\Abstraction($impl);

        $result = $abstraction->operation();

        $this->assertStringContainsString('Abstraction: Base operation', $result);
        $this->assertStringContainsString('platform A', $result);
    }

    public function testExtendedAbstractionWithImplementationB(): void
    {
        $impl = new \RefactoringGuru\Bridge\Conceptual\ConcreteImplementationB();
        $abstraction = new \RefactoringGuru\Bridge\Conceptual\ExtendedAbstraction($impl);

        $result = $abstraction->operation();

        $this->assertStringContainsString('ExtendedAbstraction', $result);
        $this->assertStringContainsString('platform B', $result);
    }
}

/**
 * =============================================
 * Bridge - RealWorld Tests
 * =============================================
 */
class BridgeRealWorldTest extends TestCase
{
    public function testSimplePageWithHTMLRenderer(): void
    {
        $renderer = new \RefactoringGuru\Bridge\RealWorld\HTMLRenderer();
        $page = new \RefactoringGuru\Bridge\RealWorld\SimplePage($renderer, 'Home', 'Welcome!');

        $result = $page->view();

        $this->assertStringContainsString('<h1>Home</h1>', $result);
        $this->assertStringContainsString('Welcome!', $result);
    }

    public function testSimplePageWithJsonRenderer(): void
    {
        $renderer = new \RefactoringGuru\Bridge\RealWorld\JsonRenderer();
        $page = new \RefactoringGuru\Bridge\RealWorld\SimplePage($renderer, 'Home', 'Welcome!');

        $result = $page->view();

        $this->assertStringContainsString('"title": "Home"', $result);
        $this->assertStringContainsString('"text": "Welcome!"', $result);
    }

    public function testProductPageWithHTMLRenderer(): void
    {
        $renderer = new \RefactoringGuru\Bridge\RealWorld\HTMLRenderer();
        $product = new \RefactoringGuru\Bridge\RealWorld\Product(
            '1', 'Laptop', 'Great laptop', '/img.jpg', 999.99
        );
        $page = new \RefactoringGuru\Bridge\RealWorld\ProductPage($renderer, $product);

        $result = $page->view();

        $this->assertStringContainsString('<h1>Laptop</h1>', $result);
        $this->assertStringContainsString('Great laptop', $result);
        $this->assertStringContainsString('/img.jpg', $result);
    }

    public function testChangeRenderer(): void
    {
        $htmlRenderer = new \RefactoringGuru\Bridge\RealWorld\HTMLRenderer();
        $jsonRenderer = new \RefactoringGuru\Bridge\RealWorld\JsonRenderer();

        $page = new \RefactoringGuru\Bridge\RealWorld\SimplePage($htmlRenderer, 'Test', 'Content');
        $htmlResult = $page->view();

        $page->changeRenderer($jsonRenderer);
        $jsonResult = $page->view();

        $this->assertStringContainsString('<h1>', $htmlResult);
        $this->assertStringContainsString('"title"', $jsonResult);
    }
}

/**
 * =============================================
 * Builder - Conceptual Tests
 * =============================================
 */
class BuilderConceptualTest extends TestCase
{
    public function testMinimalViableProduct(): void
    {
        $director = new \RefactoringGuru\Builder\Conceptual\Director();
        $builder = new \RefactoringGuru\Builder\Conceptual\ConcreteBuilder1();
        $director->setBuilder($builder);

        $director->buildMinimalViableProduct();
        $product = $builder->getProduct();

        $this->assertContains('PartA1', $product->parts);
        $this->assertCount(1, $product->parts);
    }

    public function testFullFeaturedProduct(): void
    {
        $director = new \RefactoringGuru\Builder\Conceptual\Director();
        $builder = new \RefactoringGuru\Builder\Conceptual\ConcreteBuilder1();
        $director->setBuilder($builder);

        $director->buildFullFeaturedProduct();
        $product = $builder->getProduct();

        $this->assertContains('PartA1', $product->parts);
        $this->assertContains('PartB1', $product->parts);
        $this->assertContains('PartC1', $product->parts);
        $this->assertCount(3, $product->parts);
    }

    public function testBuilderResetsAfterGetProduct(): void
    {
        $builder = new \RefactoringGuru\Builder\Conceptual\ConcreteBuilder1();
        $builder->producePartA();
        $product1 = $builder->getProduct();

        $builder->producePartB();
        $product2 = $builder->getProduct();

        $this->assertCount(1, $product1->parts);
        $this->assertCount(1, $product2->parts);
        $this->assertNotSame($product1, $product2);
    }

    public function testCustomBuild(): void
    {
        $builder = new \RefactoringGuru\Builder\Conceptual\ConcreteBuilder1();
        $builder->producePartA();
        $builder->producePartC();
        $product = $builder->getProduct();

        $this->assertContains('PartA1', $product->parts);
        $this->assertContains('PartC1', $product->parts);
        $this->assertNotContains('PartB1', $product->parts);
    }
}

/**
 * =============================================
 * Builder - RealWorld (SQL Query Builder) Tests
 * =============================================
 */
class BuilderRealWorldTest extends TestCase
{
    public function testMysqlSelectQuery(): void
    {
        $builder = new \RefactoringGuru\Builder\RealWorld\MysqlQueryBuilder();
        $sql = $builder
            ->select('users', ['name', 'email'])
            ->where('age', '18', '>')
            ->limit(10, 20)
            ->getSQL();

        $this->assertStringContainsString('SELECT name, email FROM users', $sql);
        $this->assertStringContainsString("WHERE age > '18'", $sql);
        $this->assertStringContainsString('LIMIT 10, 20', $sql);
    }

    public function testPostgresSelectQuery(): void
    {
        $builder = new \RefactoringGuru\Builder\RealWorld\PostgresQueryBuilder();
        $sql = $builder
            ->select('users', ['name'])
            ->where('age', '18', '>')
            ->limit(10, 20)
            ->getSQL();

        $this->assertStringContainsString('LIMIT 10 OFFSET 20', $sql);
    }

    public function testQueryBuilderChaining(): void
    {
        $builder = new \RefactoringGuru\Builder\RealWorld\MysqlQueryBuilder();
        $result = $builder->select('users', ['*']);

        $this->assertInstanceOf(
            \RefactoringGuru\Builder\RealWorld\SQLQueryBuilder::class,
            $result
        );
    }

    public function testMultipleWhereConditions(): void
    {
        $builder = new \RefactoringGuru\Builder\RealWorld\MysqlQueryBuilder();
        $sql = $builder
            ->select('products', ['*'])
            ->where('price', '10', '>')
            ->where('price', '100', '<')
            ->getSQL();

        $this->assertStringContainsString("price > '10' AND price < '100'", $sql);
    }
}
