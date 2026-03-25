<?php
namespace src\Piece;

use src\Board;
use src\Contract\Renderable;
use src\Enum\PieceColor;
use src\Enum\PieceType;
use src\Position;

abstract class Piece implements Renderable {
  protected PieceColor $color;
  protected Position $position;
  protected PieceType $type;

  public function __construct(PieceColor $color, Position $position) {
    $this->color = $color;
    $this->position = $position;
  }

  public function getColor(): PieceColor {
    return $this->color;
  }
  public function getPosition(): Position {
    return $this->position;
  }

  public function setPosition(Position $position): void {
    $this->position = $position;
  }
  
  public function getType(): PieceType{
    return $this->type;
  }

  public function render(): string {
    return "♟️​";
  }

  public function canMove(Board $board, Position $target): bool{
    // la pièce ne reste pas sur place ;
    if ($this->position->equals($target)){
      return false;
    }
    // la forme du déplacement est valide ;
    if (!$this->isValidMovementShape($target)){
      return false;
    }
    // la case cible n'est pas occupée par un allié ;
    if (!$this->canCapture($board, $target)){
      return false;
    }
    // si la pièce n'est pas un cavalier, le chemin est libre ;
    if ($this->type !== PieceType::KNIGHT && !$board->isPathClear($this->position, $target)){
      return false;
    }
    // si c'est un pion, les règles spéciales du pion sont respectées.
    if ($this->type == PieceType::PAWN && $target->getColumn() != $this->position->getColumn() && !$board->hasPieceAt($target)){
      return false;
    }
    return true;
  }

  abstract protected function isValidMovementShape(Position $target): bool ;
  
  protected function canCapture(Board $board, Position $target): bool {
    if ($board->hasPieceAt($target) && $board->getPieceAt($target)->color == $this->color){
      return false;
    }
    return true;
  }
}