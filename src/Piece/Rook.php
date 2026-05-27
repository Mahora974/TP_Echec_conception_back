<?php
namespace src\Piece;

use Override;
use src\Enum\PieceColor;
use src\Enum\PieceType;
use src\Position;

class Rook extends Piece {
  protected PieceType  $type = PieceType::ROOK;

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

  public function render() :string {
    if ($this->color == PieceColor::WHITE){
      return "R";
    }
    return "r";
  }
}