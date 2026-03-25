<?php
namespace src\Piece;
use src\Enum\PieceType;
use src\Position;

class Knight extends Piece {
  protected $type = PieceType::KNIGHT;

  protected function isValidMovementShape(Position $target): bool {
    // On récupère toutes les cases probables 
    // (c'est pas grave si on crée des cases impossibles dans ce tableau, car $target ne peut pas être une des ses position interdites)
    $validFinalPositions = [
      ($this->position->getRow()+2).':'.$this->position->getColumn()+1,
      ($this->position->getRow()+2).':'.$this->position->getColumn()-1,
      ($this->position->getRow()-2).':'.$this->position->getColumn()+1,
      ($this->position->getRow()-2).':'.$this->position->getColumn()-1,
      ($this->position->getRow()+1).':'.$this->position->getColumn()+2,
      ($this->position->getRow()+1).':'.$this->position->getColumn()-2,
      ($this->position->getRow()-1).':'.$this->position->getColumn()+2,
      ($this->position->getRow()-1).':'.$this->position->getColumn()-2,
    ];
    if (in_array($target->toKey(), $validFinalPositions)){
      return true;
    }
    return false;
  }
}