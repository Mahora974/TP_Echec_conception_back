<?php

require_once __DIR__ . '/vendor/autoload.php';

use src\Board;
use src\Enum\PieceColor;
use src\Factory\PieceFactory;
use src\Game;
use src\Move;
use src\Piece\Pawn;
use src\Position;


/*************************TEST GAMEPLAY*************************/
function playing(){
  $game = new Game(new Board(), new PieceFactory());
  $game->start();
  $board = $game->getBoard();
  echo $board->render();
  echo "Game Start !\n";
  while ($game->ongoing) {
    if ($game->getCurrentPlayer() == PieceColor::WHITE) {
      echo "White's turn\n"; 
    } else {
      echo "Black's turn\n"; 
    } 
    echo "What's your move ? (format: from_row:from_col to_row:to_col])";
    $handle = fopen ("php://stdin","r");
    $move = fgets($handle);
    preg_match_all('/[0-7]:[0-7]/', $move, $matches);
    while (count($matches[0]) != 2) {
      echo "Not in expected responses, retry \n";
      $handle = fopen ("php://stdin","r");
      $move = fgets($handle);
      preg_match_all('/[0-7]:[0-7]/', $move, $matches);
    }
    $from = Position::fromKey($matches[0][0]);
    $to =  Position::fromKey($matches[0][1]);
    try {
      echo "\n\n";
      $game->play(new Move($from, $to));
      echo "\n";
      echo $board->render();
    } catch (Exception $error) {
      echo $error->getMessage(), "\n";
    }
  }
  echo "Do you want to replay ? (any other response than 'y' is considered a no) :";
  $handle = fopen ("php://stdin","r");
  $move = fgets($handle);
  if (trim($move) == 'y'){
    playing();
  }
}
playing();
