<?php
namespace src\Piece;

use src\Enum\PieceColor;
use src\Enum\PieceType;
use src\Position;

class Queen extends Piece {
  protected $type = PieceType::QUEEN;

  protected function isValidMovementShape(Position $target): bool {
    // Déplacement en colonne
    if ($target->getColumn() == $this->position->getColumn()) {
      return true;
    }
    // Déplacement en ligne
    if ($target->getRow() == $this->position->getRow()) {
      return true;
    }
    // Déplacement en diagonale
    $diagonals = [];
    $column = $this->position->getColumn();
    $temprow = $row = $this->position->getRow();
    $index=1;

    while ($temprow > 0) {
      $diagonals[] = ($temprow-1).':'.$column-$index;
      $diagonals[] = ($temprow-1).':'.$column+$index;
      $temprow--;
      $index++;
    }
    $index=1;
    $temprow = $row;

    while ($temprow < 7) {
      $diagonals[] = ($temprow+1).':'.$column-$index;
      $diagonals[] = ($temprow+1).':'.$column+$index;
      $temprow++;
      $index++;
    }
    if (in_array($target->toKey(), $diagonals)){
      return true;
    }
    return false;
  }
  
  public function render() :string {
    if ($this->color == PieceColor::WHITE){
      return "Q";
    }
    return "q";
  }
}