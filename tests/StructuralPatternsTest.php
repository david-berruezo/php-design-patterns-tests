<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * =============================================
 * DataMapper Tests
 * =============================================
 */
class DataMapperTest extends TestCase
{
    public function testCanFindUserById(): void
    {
        $storage = new \DesignPatterns\Structural\DataMapper\StorageAdapter([
            1 => ['username' => 'david', 'email' => 'david@example.com'],
        ]);
        $mapper = new \DesignPatterns\Structural\DataMapper\UserMapper($storage);

        $user = $mapper->findById(1);

        $this->assertInstanceOf(\DesignPatterns\Structural\DataMapper\User::class, $user);
        $this->assertSame('david', $user->getUsername());
        $this->assertSame('david@example.com', $user->getEmail());
    }

    public function testThrowsExceptionForNotFoundUser(): void
    {
        $storage = new \DesignPatterns\Structural\DataMapper\StorageAdapter([]);
        $mapper = new \DesignPatterns\Structural\DataMapper\UserMapper($storage);

        $this->expectException(InvalidArgumentException::class);
        $mapper->findById(999);
    }

    public function testUserFromState(): void
    {
        $user = \DesignPatterns\Structural\DataMapper\User::fromState([
            'username' => 'daniel',
            'email' => 'daniel@test.com',
        ]);

        $this->assertSame('daniel', $user->getUsername());
        $this->assertSame('daniel@test.com', $user->getEmail());
    }

    public function testStorageAdapterReturnsNullForMissingId(): void
    {
        $storage = new \DesignPatterns\Structural\DataMapper\StorageAdapter([]);
        $this->assertNull($storage->find(42));
    }
}

/**
 * =============================================
 * DependencyInjection Tests
 * =============================================
 */
class DependencyInjectionTest extends TestCase
{
    public function testDatabaseConfigurationGetters(): void
    {
        $config = new \DesignPatterns\Structural\DependencyInjection\DatabaseConfiguration(
            'localhost', 3306, 'root', 'secret'
        );

        $this->assertSame('localhost', $config->getHost());
        $this->assertSame(3306, $config->getPort());
        $this->assertSame('root', $config->getUsername());
        $this->assertSame('secret', $config->getPassword());
    }

    public function testDatabaseConnectionDsn(): void
    {
        $config = new \DesignPatterns\Structural\DependencyInjection\DatabaseConfiguration(
            'db.server.com', 5432, 'admin', 'p@ss'
        );
        $connection = new \DesignPatterns\Structural\DependencyInjection\DatabaseConnection($config);

        $this->assertSame('admin:p@ss@db.server.com:5432', $connection->getDsn());
    }

    public function testDifferentConfigurationProducesDifferentDsn(): void
    {
        $config1 = new \DesignPatterns\Structural\DependencyInjection\DatabaseConfiguration(
            'host1', 3306, 'user1', 'pass1'
        );
        $config2 = new \DesignPatterns\Structural\DependencyInjection\DatabaseConfiguration(
            'host2', 5432, 'user2', 'pass2'
        );

        $conn1 = new \DesignPatterns\Structural\DependencyInjection\DatabaseConnection($config1);
        $conn2 = new \DesignPatterns\Structural\DependencyInjection\DatabaseConnection($config2);

        $this->assertNotSame($conn1->getDsn(), $conn2->getDsn());
    }
}

/**
 * =============================================
 * FluentInterface Tests
 * =============================================
 */
class FluentInterfaceTest extends TestCase
{
    public function testCanBuildSimpleQuery(): void
    {
        $sql = new \DesignPatterns\Structural\FluentInterface\Sql();

        $query = (string) $sql
            ->select(['name', 'email'])
            ->from('users', 'u')
            ->where('id = 1');

        $this->assertSame(
            'SELECT name, email FROM users AS u WHERE id = 1',
            $query
        );
    }

    public function testCanBuildQueryWithMultipleConditions(): void
    {
        $sql = new \DesignPatterns\Structural\FluentInterface\Sql();

        $query = (string) $sql
            ->select(['*'])
            ->from('orders', 'o')
            ->where('status = 1')
            ->where('total > 100');

        $this->assertSame(
            'SELECT * FROM orders AS o WHERE status = 1 AND total > 100',
            $query
        );
    }

    public function testMethodChainingReturnsSelf(): void
    {
        $sql = new \DesignPatterns\Structural\FluentInterface\Sql();

        $result = $sql->select(['id']);
        $this->assertInstanceOf(\DesignPatterns\Structural\FluentInterface\Sql::class, $result);

        $result = $sql->from('table', 't');
        $this->assertInstanceOf(\DesignPatterns\Structural\FluentInterface\Sql::class, $result);

        $result = $sql->where('1=1');
        $this->assertInstanceOf(\DesignPatterns\Structural\FluentInterface\Sql::class, $result);
    }
}

/**
 * =============================================
 * Registry Tests
 * =============================================
 */
class RegistryTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset static state by setting a fresh service
        $service = new \DesignPatterns\Structural\Registry\Service();
        \DesignPatterns\Structural\Registry\Registry::set(
            \DesignPatterns\Structural\Registry\Registry::LOGGER,
            $service
        );
    }

    public function testCanSetAndGetService(): void
    {
        $service = new \DesignPatterns\Structural\Registry\Service();
        \DesignPatterns\Structural\Registry\Registry::set(
            \DesignPatterns\Structural\Registry\Registry::LOGGER,
            $service
        );

        $retrieved = \DesignPatterns\Structural\Registry\Registry::get(
            \DesignPatterns\Structural\Registry\Registry::LOGGER
        );

        $this->assertSame($service, $retrieved);
    }

    public function testThrowsExceptionForInvalidKeyOnSet(): void
    {
        $this->expectException(InvalidArgumentException::class);

        \DesignPatterns\Structural\Registry\Registry::set(
            'invalid_key',
            new \DesignPatterns\Structural\Registry\Service()
        );
    }

    public function testThrowsExceptionForInvalidKeyOnGet(): void
    {
        $this->expectException(InvalidArgumentException::class);

        \DesignPatterns\Structural\Registry\Registry::get('invalid_key');
    }
}

/**
 * =============================================
 * ServiceLocator Tests
 * =============================================
 */
class ServiceLocatorTest extends TestCase
{
    public function testCanAddAndGetInstance(): void
    {
        $locator = new \DesignPatterns\More\ServiceLocator\ServiceLocator();
        $service = new \DesignPatterns\More\ServiceLocator\LogService();

        $locator->addInstance(\DesignPatterns\More\ServiceLocator\LogService::class, $service);

        $this->assertTrue($locator->has(\DesignPatterns\More\ServiceLocator\LogService::class));
        $this->assertSame($service, $locator->get(\DesignPatterns\More\ServiceLocator\LogService::class));
    }

    public function testCanAddClassAndInstantiateOnGet(): void
    {
        $locator = new \DesignPatterns\More\ServiceLocator\ServiceLocator();
        $locator->addClass(\DesignPatterns\More\ServiceLocator\LogService::class, []);

        $this->assertTrue($locator->has(\DesignPatterns\More\ServiceLocator\LogService::class));

        $service = $locator->get(\DesignPatterns\More\ServiceLocator\LogService::class);
        $this->assertInstanceOf(\DesignPatterns\More\ServiceLocator\LogService::class, $service);
    }

    public function testHasReturnsFalseForUnregisteredService(): void
    {
        $locator = new \DesignPatterns\More\ServiceLocator\ServiceLocator();

        $this->assertFalse($locator->has('NonExistentService'));
    }

    public function testGetReturnsSameInstanceOnSubsequentCalls(): void
    {
        $locator = new \DesignPatterns\More\ServiceLocator\ServiceLocator();
        $locator->addClass(\DesignPatterns\More\ServiceLocator\LogService::class, []);

        $first = $locator->get(\DesignPatterns\More\ServiceLocator\LogService::class);
        $second = $locator->get(\DesignPatterns\More\ServiceLocator\LogService::class);

        $this->assertSame($first, $second);
    }
}
