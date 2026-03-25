<?php 
namespace src\Exception;

class NoPieceException extends ChessException {
  protected $message = "No piece is seleted. Please select piece";
  protected $code = 420001;
}