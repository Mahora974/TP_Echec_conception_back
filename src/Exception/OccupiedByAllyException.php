<?php
namespace src\Exception;

use Exception;

class OccupiedByAllyException extends Exception {
  protected $message = "You can't capture the piece, it's one of yours.";
  protected $code = 42004;
}