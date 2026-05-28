<?php

require_once __DIR__ . '/vendor/autoload.php';

use src\Board;
use src\Enum\PieceColor;
use src\Factory\PieceFactory;
use src\Game;
use src\Move;
use src\Piece\Pawn;
use src\Position;

$game = new Game(new Board(), new PieceFactory());
$game->start();
$board = $game->getBoard();

echo "Game Start !";



/*************************TEST GAMEPLAY*************************/
echo $board->render();
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(1,4), new Position(3,4)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(6,4), new Position(4,4)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(0,1), new Position(2,2)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(7,6), new Position(5,5)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(1,3), new Position(2,3)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(7,5), new Position(4,2)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(0,2), new Position(4,6)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(6,7), new Position(4,7)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(0,3), new Position(3,6)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(7,4), new Position(7,6)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(0,4), new Position(0,2)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(4,7), new Position(3,6)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(1,7), new Position(3,7)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(3,6), new Position(2,7)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(1,0), new Position(3,0)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(2,7), new Position(1,6)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(3,0), new Position(4,0)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(6,1), new Position(4,1)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(4,0), new Position(5,1)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(1,6), new Position(0,7)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(5,1), new Position(6,0)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(4,2), new Position(2,0)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(6,0), new Position(7,1)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}
try {
  echo "\n\n";
  echo $game->play(new Move(new Position(2,0), new Position(1,1)));
  echo "\n";
  echo $board->render();
} catch (Exception $error) {
  echo $error->getMessage(), "\n";
}

/***Mat du berger***/
// try {
//   echo "\n\n";
//   $game->play(new Move(new Position(0,3), new Position(4,7)));
//   echo "\n";
//   echo $board->render(); 
// } catch (Exception $error) {
//   echo $error->getMessage(), "\n";
// } 
// try {
//   echo "\n\n";
//   echo $game->play(new Move(new Position(6,5), new Position(4,5)));
//   echo "\n";
//   echo $board->render(); 
// } catch (Exception $error) {
//   echo $error->getMessage(), "\n";
// }
// try {
//   echo "\n\n";
//   echo $game->play(new Move(new Position(6,3), new Position(5,3)));
//   echo "\n";
//   echo $board->render(); 
// } catch (Exception $error) {
//   echo $error->getMessage(), "\n";
// }
// try {
//   echo "\n\n";
//   echo $game->play(new Move(new Position(0,5), new Position(3,2)));
//   echo "\n";
//   echo $board->render();
// } catch (Exception $error) {
//   echo $error->getMessage(), "\n";
// }
// try {
//   echo "\n\n";
//   echo $game->play(new Move(new Position(7,1), new Position(5,2)));
//   echo "\n";
//   echo $board->render();
// } catch (Exception $error) {
//   echo $error->getMessage(), "\n";
// }
// try {
//   echo "\n\n";
//   echo $game->play(new Move(new Position(4,7), new Position(6,5)));
//   echo "\n";
//   echo $board->render();
// } catch (Exception $error) {
//   echo $error->getMessage(), "\n";
// }