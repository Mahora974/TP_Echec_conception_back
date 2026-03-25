<?php
namespace src\Piece;
use src\Enum\PieceType;
use src\Position;

class Rook extends Piece {
  protected $type = PieceType::ROOK;

  protected function isValidMovementShape(Position $target): bool {
    // Déplacement en colonne
    if ($target->getColumn() == $this->position->getColumn()) {
      return true;
    }
    // Déplacement en ligne
    if ($target->getRow() == $this->position->getRow()) {
      return true;
    }
    return false;
  }
}