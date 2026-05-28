<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use src\Board;
use src\Enum\PieceColor;
use src\Exception\InvalidMoveException;
use src\Piece\Pawn;
use src\Position;

final class PawnTest extends TestCase
{
  public function testCanMovePawn(): void {
    // Mise en place du PLteau
    $board = new Board();
    $pawnW = new Pawn(PieceColor::WHITE, new Position(1,3));
    $pawnB = new Pawn(PieceColor::BLACK, new Position(6,3));
    $board->placePiece($pawnW);
    $board->placePiece($pawnB);

    // Avancement de base
    $this->assertTrue($pawnW->canMove($board, new Position(2,3)));
    $this->assertTrue($pawnB->canMove($board, new Position(5,3)));

    // Avancement de deux cases si pas encore bougé
    $this->assertTrue($pawnW->canMove($board, new Position(3,3)));
    $this->assertTrue($pawnB->canMove($board, new Position(4,3)));

    // Possibilité d'aller en diagonale pour prendre
    $board->placePiece(new Pawn(PieceColor::BLACK, new Position(2,4)));
    $this->assertTrue($pawnW->canMove($board, new Position(2,4)));
    $board->placePiece(new Pawn(PieceColor::WHITE, new Position(5,4)));
    $this->assertTrue($pawnB->canMove($board, new Position(5,4)));
  }

  public function testToMuchSpacesPawn():void {
    // Mise en place du PLteau
    $board = new Board();
    $pawn = new Pawn(PieceColor::WHITE, new Position(1,3));
    $board->placePiece($pawn);
    $this->expectException(InvalidMoveException::class);
    $pawn->canMove($board, new Position(4,3));
  }

  public function testSideWayPawn():void {
    // Mise en place du PLteau
    $board = new Board();
    $pawn = new Pawn(PieceColor::WHITE, new Position(1,3));
    $board->placePiece($pawn);
    $this->expectException(InvalidMoveException::class);
    $pawn->canMove($board, new Position(1,4));
  }

  public function testBackwardWhitePawn():void {
    // Mise en place du PLteau
    $board = new Board();
    $pawn = new Pawn(PieceColor::WHITE, new Position(1,3));
    $board->placePiece($pawn);
    $this->expectException(InvalidMoveException::class);
    $pawn->canMove($board, new Position(0,3));
  }

  public function testBackwardBlackPawn():void {
    // Mise en place du PLteau
    $board = new Board();
    $pawn = new Pawn(PieceColor::BLACK, new Position(6,3));
    $board->placePiece($pawn);
    $this->expectException(InvalidMoveException::class);
    $pawn->canMove($board, new Position(7,3));
  }
}