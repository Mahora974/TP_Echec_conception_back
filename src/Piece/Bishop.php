<?php
namespace src\Piece;

use src\Enum\PieceColor;
use src\Enum\PieceType;
use src\Position;

class Bishop extends Piece {
  protected PieceType $type = PieceType::BISHOP;

  protected function isValidMovementShape(Position $target): bool {
    // Déplacement en diagonale
    $diagonals = [];
    $column = $this->position->getColumn();
    $temprow = $row = $this->position->getRow();
    $index=1;

    while ($temprow > 0) {
      if ($column-$index > -1) {
        $diagonals[] = ($temprow-1).':'.$column-$index;
      }
      if ($column+$index <8) {
        $diagonals[] = ($temprow-1).':'.$column+$index;
      }
      $temprow--;
      $index++;
    }
    $index=1;
    $temprow = $row;

    while ($temprow < 7) {
      if ($column-$index > -1) {
        $diagonals[] = ($temprow+1).':'.$column-$index;
      }
      if ($column+$index < 8) {
        $diagonals[] = ($temprow+1).':'.$column+$index;
      }
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
      return "B";
    }
    return "b";
  }
}