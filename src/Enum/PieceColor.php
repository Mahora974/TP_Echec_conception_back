<?php
namespace src\Enum;

enum PieceColor {
  case WHITE;
  case BLACK;
  
  public function opposite(): PieceColor {
    if ($this == self::WHITE) {
      return self::BLACK;
    } else if ($this == self::BLACK) {
      return self::WHITE;
    }
  }
}

