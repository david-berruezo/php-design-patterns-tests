<?php
declare(strict_types=1);

namespace Test\Patrones\Structural\DataMapper\Test;

use InvalidArgumentException;
use Test\Patrones\Structural\DataMapper\StorageAdapter;
use Test\Patrones\Structural\DataMapper\User;
use Test\Patrones\Structural\DataMapper\UserMapper;
use PHPUnit\Framework\TestCase;

class DataMapperTest extends TestCase
{
    public function testCanMapUserFromStorage()
    {
        $storage = new StorageAdapter([1 => ['username' => 'someone', 'email' => 'someone@example.com']]);
        $mapper = new UserMapper($storage);

        $user = $mapper->findById(1);

        $this->assertInstanceOf(User::class, $user);
    }

    public function testWillNotMapInvalidData()
    {
        $this->expectException(InvalidArgumentException::class);

        $storage = new StorageAdapter([]);
        $mapper = new UserMapper($storage);

        $mapper->findById(1);
    }

}
