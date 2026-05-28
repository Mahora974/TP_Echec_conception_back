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
  protected int $moved = 0;

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
    $this->moved++;
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
      throw new OccupiedByAllyException();
    }
    // si la pièce n'est pas un cavalier et que le chemin est libre ;
    if ($this->type !== PieceType::KNIGHT && !$board->isPathClear($this->position, $target)){
      return false;
    }
    // si c'est un pion, les règles spéciales du pion sont respectées.
    if ($this->type == PieceType::PAWN && $target->getColumn() != $this->position->getColumn() && !$board->hasPieceAt($target)){
      if ($board->getPassingPawn() == $target) {
          $board->clearGhostPawn();
          $board->removePieceAt(new Position($this->color== PieceColor::WHITE?$target->getRow()-1:$target->getRow()+1,$target->getColumn()));
      } else {
        return false;
      }
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

  public function canCastle(Board $board, Position $target) {
    // check si on est en train de roquer d'abors, pour pas être bloquant
    if ($this->type !== PieceType::KING) {
        return false;
    }
    if ($target->getColumn() !== 6 && $target->getColumn() !==  2) {
        return false;
    }
    if ($this->moved > 0) {
      return false;
    }

  // Coordonées de la tour en fct° du coté
    if ($target->getColumn() == 6) {
      $rook = $board->getPieces()[$this->position->getRow().':7'];
    }
    if ($target->getColumn() == 2) {
      $rook = $board->getPieces()[$this->position->getRow().':0'];
    }
    // 
    if (!isset($rook) || $rook->type !== PieceType::ROOK || $rook->moved ){
      return false;
    } 
    if (!$board->isPathClear($rook->getPosition(), $this->position)){
      return false;
    }
    return true;
  }
}