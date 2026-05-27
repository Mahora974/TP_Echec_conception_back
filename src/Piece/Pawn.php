<?php
namespace src\Piece;
use src\Enum\PieceType;
use src\Enum\PieceColor;
use src\Position;

class Pawn extends Piece {
  protected PieceType  $type = PieceType::PAWN;

  protected function isValidMovementShape(Position $target): bool {
    // On récupère toutes les cases probables 
    // (c'est pas grave si on crée des cases impossibles dans ce tableau, car $target ne peut pas être une des ses position interdites)
    if ($this->color == PieceColor::WHITE) {
      $validFinalPositions = [($this->position->getRow()+1).':'.$this->position->getColumn(),($this->position->getRow()+1).':'.$this->position->getColumn()+1, ($this->position->getRow()+1).':'.$this->position->getColumn()-1];
    } else {
      $validFinalPositions = [($this->position->getRow()-1).':'.$this->position->getColumn(),($this->position->getRow()-1).':'.$this->position->getColumn()+1, ($this->position->getRow()-1).':'.$this->position->getColumn()-1];
    }
    if (($this->color == PieceColor::WHITE && $this->position->getRow() == 1)) {
      $validFinalPositions[] = ($this->position->getRow()+2).':'.$this->position->getColumn();
    } 

    if (($this->color == PieceColor::BLACK && $this->position->getRow() == 6)){
      $validFinalPositions[] = ($this->position->getRow()-2).':'.$this->position->getColumn();
    } 
    if (in_array($target->toKey(), $validFinalPositions)){
      return true;
    }
    return false;
  }
  
  public function render() :string {
    if ($this->color == PieceColor::WHITE){
      return "P";
    }
    return "p";
  }
}