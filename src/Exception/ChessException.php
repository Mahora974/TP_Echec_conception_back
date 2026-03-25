<?php
namespace src\Exception;

use Exception;

class ChessException extends Exception {
  protected $message = "Not respecting chess rules";
  protected $code = 42000;
}