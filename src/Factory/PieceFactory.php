<?php
namespace src\Factory;

use src\Enum\PieceColor;
use src\Enum\PieceType;
use src\Exception\ChessException;
use src\Piece\Bishop;
use src\Piece\King;
use src\Piece\Knight;
use src\Piece\Pawn;
use src\Piece\Piece;
use src\Piece\Queen;
use src\Piece\Rook;
use src\Position;

class PieceFactory {
  public function create(PieceType $type, PieceColor $color, Position $position): Piece{
    switch ($type){
      case PieceType::ROOK:
        return new Rook($color, $position);
        break;
      case PieceType::KNIGHT:
        return new Knight($color, $position);
        break;
      case PieceType::BISHOP:
        return new Bishop($color, $position);
        break;
      case PieceType::QUEEN:
        return new Queen($color, $position);
        break;
      case PieceType::KING:
        return new King($color, $position);
        break;
      case PieceType::PAWN:
        return new Pawn($color, $position);
        break;
      default:
        throw new ChessException("No type of piece specified");
    }
  }
}