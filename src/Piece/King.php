<?php
namespace src\Piece;
use src\Enum\PieceType;
use src\Position;

class King extends Piece {
  protected $type = PieceType::KING;

  protected function isValidMovementShape(Position $target): bool {
    // On récupère toutes les cases probables 
    // (c'est pas grave si on crée des cases impossibles dans ce tableau, car $target ne peut pas être une des ses position interdites)
    $validFinalPositions = [
      ($this->position->getRow()-1).':'.$this->position->getColumn()-1, 
      ($this->position->getRow()-1).':'.$this->position->getColumn(), 
      ($this->position->getRow()-1).':'.$this->position->getColumn()+1, 
      ($this->position->getRow()).':'.$this->position->getColumn()-1, 
      ($this->position->getRow()).':'.$this->position->getColumn()+1,
      ($this->position->getRow()+1).':'.$this->position->getColumn()-1, 
      ($this->position->getRow()+1).':'.$this->position->getColumn(), 
      ($this->position->getRow()+1).':'.$this->position->getColumn()+1, 
    ];
    if (in_array($target->toKey(), $validFinalPositions)){
      return true;
    }
    return false;
  }
}