<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use src\Position;

final class PositionTest extends TestCase
{
    public function testCanBeCreatedFromValidKey(): void {
        $key = '4:3';
        $position = Position::fromKey($key);
        $this->assertSame($position->toKey(), $key);
    }

    public function testCannotBeCreatedFromInvalidKey(): void {
        // Mauvais séparateur
        $this->expectException(InvalidArgumentException::class);
        Position::fromKey('4,7');
        // Mauvaise ligne (> 7) 
        $this->expectException(InvalidArgumentException::class);
        Position::fromKey('8:4');
        // Mauvaise colonne (< 0)
        $this->expectException(InvalidArgumentException::class);
        Position::fromKey('4:-1');
    }

    public function testArePositionsEquals(): void {
        $position1 = new Position(2,2);
        $position2 = new Position(4,6);
        $this->assertSame($position1->equals($position1), true);
        $this->assertSame($position2->equals($position1), false);        
    }
}