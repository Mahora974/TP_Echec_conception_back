<?php
namespace src\Exception;

use Exception;

class WrongTurnException extends Exception {
  protected $message = "This is not your turn. Please wait.";
  protected $code = 42003;
}