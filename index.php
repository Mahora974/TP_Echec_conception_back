<?php

require_once __DIR__ . '/vendor/autoload.php';

use src\Board;
use src\Factory\PieceFactory;
use src\Game;
use src\Move;
use src\Position;

$game = new Game(new Board(), new PieceFactory());
$game->start();
$board = $game->getBoard();


/*************************TEST GAMEPLAY*************************/

echo $board->render();
echo "\n\n";
echo $game->play(new Move(new Position(1,4), new Position(3,4)));
echo "\n";
echo $board->render();
echo "\n\n";
$game->play(new Move(new Position(6,4), new Position(4,4)));
echo "\n";
echo $board->render();
echo "\n\n";
$game->play(new Move(new Position(0,3), new Position(4,7)));
echo "\n";
echo $board->render();
echo "\n\n";
$game->play(new Move(new Position(6,5), new Position(4,5)));
echo "\n";
echo $board->render(); 