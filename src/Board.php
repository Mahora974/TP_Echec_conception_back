<?php
namespace src;
use src\Contract\Renderable;
use src\Enum\PieceColor;
use src\Enum\PieceType;
use src\Exception\NoPieceException;
use src\Piece\Piece;
use src\Position;

class Board implements Renderable {
  private array $pieces = [];

  public function placePiece(Piece $piece): void {
    $this->pieces[$piece->getPosition()->toKey()] = $piece;
  }
  public function getPieceAt(Position $position): ?Piece{
    if (!$this->hasPieceAt($position)) {
      throw new NoPieceException();
    }
    return $this->pieces[$position->toKey()];
  }
  public function hasPieceAt(Position $position): bool{
    return isset($this->pieces[$position->toKey()]);
  }

  public function removePieceAt(Position $position): void {
    $this->pieces[$position->toKey()] = null;
  }

  public function movePiece(Position $from, Position $to): void {
    if (!$this->hasPieceAt($from)) {
      throw new NoPieceException();
    }
    $piece = $this->pieces[$from->toKey()];
    if ($piece->canMove($this, $to)) {
      $this->pieces[$to->toKey()] = $piece;
      $this->removePieceAt($from);
    }
  }
  public function isPathClear(Position $from, Position $to): bool{
    $row = $from->getRow();
    $column = $from->getColumn();
    if ($from->getColumn() === $to->getColumn()){
      $diff = $to->getRow() - $from->getRow();
      for ($i = 0; $i < abs($diff); $i++){
        $modifier = 1;
        if ($diff< 0){
          $modifier *=-1;
        }
        $row += $modifier;
        if (isset($this->pieces[$row.':'.$to->getColumn()])){
          return false;
        }
      }
    } else if ($from->getRow() === $to->getRow()){
      $diff = $to->getColumn() - $from->getColumn();
      for ($i = 0; $i < abs($diff); $i++){
        $modifier = 1;
        if ($diff< 0){
          $modifier *=-1;
        }
        $column += $modifier;
        if (isset($this->pieces[$to->getRow().':'.$column])){
          return false;
        }
      }
    } else {
      $diff = $to->getColumn() - $from->getColumn();
      $diffColumn = abs($diff);
      $diffRow= $to->getRow() - $from->getRow();
      
      for ($i = 0; $i < abs($diff); $i++){
        $modifierRow = 1;
        if ($diffRow< 0){
          $modifierRow *=-1;
        }
        $row += $modifierRow;
        $modifierCol = 1;
        if ($diffColumn< 0){
          $modifierCol *=-1;
        }
        $column += $modifierCol;
        if (isset($this->pieces[$row.':'.$column])){
          return false;
        }
      }
    }
    return true;
  }

  public function getPieces(): array {
    return $this->pieces;
  }
  public function getKingPosition(PieceColor $color): ?Position {
    foreach ($this->pieces as $position=>$piece){
      if (isset($piece) && $piece->getType() == PieceType::KING && $piece->getColor() ==  $color){
        return Position::fromKey($position);
      }
    }

  }

  public function render(): string {
    $result="|-|-|-|-|-|-|-|-|\n";
    for ($row = 0; $row < 8; $row++){
      $result.= "|";
      for ($col = 0; $col < 8; $col++){
        if (isset($this->pieces[$row.':'.$col])){
          $result .= $this->pieces[$row.':'.$col]->render();
        } else {
          $result .= " ";
        }
        $result.= "|";
      }
      $result .="\n|-|-|-|-|-|-|-|-|\n";
    }
    return $result;
  }
}