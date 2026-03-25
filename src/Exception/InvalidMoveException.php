<?php 
namespace src\Exception;

class InvalidMoveException extends ChessException {
  protected $message = "The move is not valid. Select an other tile.";
  protected $code = 420002;
}