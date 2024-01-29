<?php
declare(strict_types=1);
namespace Test\Patrones\Structural\Adapter\Tests;

use Test\Patrones\Structural\Adapter\Book;
use Test\Patrones\Structural\Adapter\EBook;
use Test\Patrones\Structural\Adapter\EBookAdapter;
use Test\Patrones\Structural\Adapter\PaperBook;
use Test\Patrones\Structural\Adapter\Kindle;

use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase
{
    public function testCanTurnPageOnBook()
    {
        $book = new PaperBook();
        $book->open();
        $book->turnPage();

        $this->assertSame(2, $book->getPage());
    }

    public function testCanTurnPageOnKindleLikeInANormalBook()
    {
        $kindle = new Kindle();
        $book = new EBookAdapter($kindle);

        $book->open();
        $book->turnPage();

        $this->assertSame(2, $book->getPage());
    }
}
