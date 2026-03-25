<?php

class Position {
  private int $row;
  private int $column;

  /**
   * @param int $row
   * @param int $column
   * @return void
   */
  public function __construct(int $row, int $column){
    $this->setRow($row);
    $this->setColumn($column);
  }

  /**
   * @param int $row
   * @return void
   */
  public function setRow(int $row):void {
    if ($row < 0 || $row >7 ){
      return;
    }
    $this->row = $row;
  }

  /**
   * @param int $column
   * @return void
   */
  public function setColumn(int $column):void {
    if ($column < 0 || $column >7 ){
      return;
    }
    $this->column = $column;
  }

  public function getRow(): int {
    return $this->row;
  }

  public function getColumn(): int {
    return $this->column;
  }

  public function equals(Position $other): bool {
    return $this->column == $other->getColumn() && $this->row == $other->getRow();
  }

  public function toKey(): string {
    return $this->row.':'.$this->column;
  }

  public static function fromKey(string $key): Position {
    if (preg_match( '^[0-7]:[0-7]\z', $key)){
      [$row, $column] = explode(':', $key);
      return new Position($row, $column);
    }
  }
}