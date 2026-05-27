<?php
namespace src\Piece;

use Exception;
use src\Board;
use src\Contract\Renderable;
use src\Enum\PieceColor;
use src\Enum\PieceType;
use src\Exception\InvalidMoveException;
use src\Exception\OccupiedByAllyException;
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
      throw new InvalidMoveException("The piece is not moving. Please select a tile");
    }
    // la forme du déplacement est valide ;
    if (!$this->isValidMovementShape($target)){
      throw new InvalidMoveException();
    }
    // la case cible n'est pas occupée par un allié ;
    if (!$this->canCapture($board, $target)){
      throw new OccupiedByAllyException();
    }
    // si la pièce n'est pas un cavalier et que le chemin est libre ;
    if ($this->type !== PieceType::KNIGHT && !$board->isPathClear($this->position, $target)){
      throw new InvalidMoveException();
    }
    // si c'est un pion, les règles spéciales du pion sont respectées.
    if ($this->type == PieceType::PAWN && $target->getColumn() != $this->position->getColumn() && !$board->hasPieceAt($target)){
      throw new InvalidMoveException();
    }
    return true;
  }

  abstract protected function isValidMovementShape(Position $target): bool ;
  
  protected function canCapture(Board $board, Position $target): bool {
    if ($board->hasPieceAt($target)){
      if ($board->getPieceAt($target)->color == $this->color) {
        return false;
      }
    }
    return true;
  }
}