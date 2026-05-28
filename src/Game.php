<?php

namespace src;

use Exception;
use src\Enum\PieceColor;
use src\Enum\PieceType;
use src\Factory\PieceFactory;
use src\Board;
use src\Exception\ChessException;
use src\Exception\InvalidMoveException;
use src\Exception\NoPieceException;
use src\Exception\WrongTurnException;
use src\Piece\Piece;
use src\Move;
use src\Piece\Pawn;

class Game {
  private Board $board;
  private PieceColor $currentPlayer;
  private PieceFactory $pieceFactory;
  public bool $ongoing = true;
  protected null|Position $canBeEatenInPassing = null;

  public function __construct(Board $board, PieceFactory $pieceFactory) {
    $this->board = $board;
    $this->currentPlayer = PieceColor::WHITE;
    $this->pieceFactory = $pieceFactory;
  }
  public function start(): void {
    $this->setupPieces();
  }

  public function end(PieceColor $winner): void {
    $this->ongoing = false;
    if ($winner == PieceColor::WHITE){
      echo "White won !";
    } else if ($winner == PieceColor::BLACK){
      echo "Black won !";
    } else {
      echo "Nobody won.";
    }
  }
  public function getBoard(): Board {
    return $this->board;
  }
  public function getCurrentPlayer(): PieceColor {
    return $this->currentPlayer;
  }
  public function play(Move $move): void {
    if (!$this->ongoing) {
      throw new ChessException("The game has ended.");
    }
    if (!$this->board->hasPieceAt($move->getFrom())){
      throw new NoPieceException();
    }
    $piece = $this->board->getPieceAt($move->getFrom());
    if ($piece->getColor() !== $this->currentPlayer){
      throw new WrongTurnException();
    }
    if ($piece->canCastle($this->board, $move->getTo())){
      // Vérifier qu'aucune case par laquelle passe le roi est en échec
      if ($this->isCheck($this->currentPlayer)) {
        throw new InvalidMoveException("You are checked");
      }
      if ($move->getTo()->getColumn() == 6) {
        $intermediateSquare = new Position($piece->getPosition()->getRow(), 5);
        $rook = $this->board->getPieces()[$piece->getPosition()->getRow().':7'];
      }
      if ($move->getTo()->getColumn() == 2) {
        $intermediateSquare = new Position($piece->getPosition()->getRow(), 3);
        $rook = $this->board->getPieces()[$piece->getPosition()->getRow().':0'];
      }
      if (isset($intermediateSquare) && isset($rook)) {
        $this->board->movePiece($move->getFrom(), $intermediateSquare);
        if ($this->isCheck($this->currentPlayer)) {
          $this->board->movePiece($intermediateSquare, $move->getFrom());
          throw new InvalidMoveException("You can't castle");
        }
        $this->board->movePiece($intermediateSquare, $move->getTo());
        if ($this->isCheck($this->currentPlayer)) {
          $this->board->movePiece($move->getTo(), $intermediateSquare);
          throw new InvalidMoveException("You can't castle");
        }
        // Déplacer la tour
        $this->board->movePiece($rook->getPosition(), $intermediateSquare);
      }

    } else {
      try {
        if ($piece->canMove($this->board, $move->getTo())) {
          $this->board->movePiece($move->getFrom(), $move->getTo());
          // vérifier qu'on ne met ou ne laisse pas le roi à découvert 
          if ($this->isCheck($this->currentPlayer)) {
            $this->board->movePiece($move->getTo(), $move->getFrom());
            throw new InvalidMoveException("You are checked");
          }
          if ($piece->getType() == PieceType::PAWN && ($piece->getColor() == PieceColor::WHITE && $move->getTo()->getRow() == 7) || ($piece->getColor() == PieceColor::BLACK && $move->getTo()->getRow() == 0)) {
            $this->promote($piece);
          }
          if ($piece->getType() == PieceType::PAWN && abs($move->getFrom()->getRow() - $move->getTo()->getRow()) == 2){
            $this->canBeEatenInPassing = $piece->getPosition();
            $this->board->ghostPawn(new Position($move->getTo()->getRow() + ($move->getFrom()->getRow() - $move->getTo()->getRow())/2, $move->getTo()->getColumn()));
          } else if (!is_null($this->canBeEatenInPassing)){
            $this->canBeEatenInPassing = null;
            $this->board->clearGhostPawn();
          }
        }
      } catch (Exception) {
        throw new InvalidMoveException();
      }
    }
    $this->switchPlayer();
    if ($this->isCheck($this->currentPlayer)){
      if ($this->isCheckmate($this->currentPlayer)){
        echo "CHECKMATE\n";
        $this->switchPlayer();
        $this->end($this->currentPlayer);
      } else {
        echo "CHECK";
      }
    }
  }

  private function promote(Piece $piece){
    echo "In which piece do yo want your pawn to be promotted ? \n";
    echo "- q for queen \n";
    echo "- r for rook \n";
    echo "- b for bishop \n";
    echo "- n for knight \n";
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    while (!in_array(strtolower(trim($line)), ['q', 'queen', 'r', 'rook', 'b', 'bishop', 'n', 'knight'])) {
      echo "Not in expected responses, retry \n";
      $handle = fopen ("php://stdin","r");
      $line = fgets($handle);
    }
    $response = strtolower(trim($line));
    switch($response){
      case "q":
      case "queen": 
        $type = PieceType::QUEEN;
        break;
      case "r":
      case "rook": 
        $type = PieceType::ROOK;
        break;
      case "b":
      case "bishop": 
        $type = PieceType::BISHOP;
        break;
      case "n":
      case "knight": 
        $type = PieceType::KNIGHT;
        break;
      default :
        $type = PieceType::QUEEN;
        break;
    }
      $promotion = $this->pieceFactory->create($type, $piece->getColor(), $piece->getPosition());
      $this->board->placePiece($promotion);

  }

  public function isCheck(PieceColor $color): bool  {
    $kingPosition = $this->board->getKingPosition($color);
    foreach ($this->board->getPieces() as $poition=>$piece) {
      if ($piece instanceof Piece && $piece->getColor() !== $color){
        try {
          if ($piece->canMove($this->board, $kingPosition)){
            return true;
          }
        } catch (Exception $error) {
        }
      }
    }
    return false;
  }

  private function isCheckmate(PieceColor $color): bool{
    $kingPosition = $this->board->getKingPosition($color);
    $king = $this->board->getPieceAt($kingPosition);
    $kingRow = $kingPosition->getRow();
    $kingColumn = $kingPosition->getColumn();
    // Est-ce qu'il peut fuir sans être en échec ?
    for ($i = 0; $i<8; $i++){
      $row = $kingRow;
      if ($i < 3) {
        if ($row == 7) {
          continue;
        }
        $row++;
      } else if ($i > 4) {
        if ($row == 0) {
          continue;
        }
        $row--;
      }
      $column = $kingColumn;
      if ($i == 0 || $i == 3 || $i == 5) {
        if ($column == 7) {
          continue;
        }
        $column++;
      } else if ($i == 2 || $i == 4 || $i == 7) {
        if ($column == 0) {
          continue;
        }
        $column--;
      }
      $possibleHideout = new Position($row, $column);
      // S'il peut bouger sur au moins une case
      try {
        if ($king->canMove($this->board, $possibleHideout)) {
          $testPiece = $this->board->getPieceAt($possibleHideout);
          $this->board->movePiece($kingPosition, $possibleHideout);
          // Sans être en échec, il n'est pas échec et mat
          try{
            if (!$this->isCheck($this->currentPlayer)){
              $this->board->movePiece($possibleHideout, $kingPosition);
              $this->board->placePiece($testPiece);
              return false;
            }
            $this->board->movePiece($possibleHideout, $kingPosition);
            $this->board->placePiece($testPiece);
          }catch(Exception $error){
            if ($error instanceof ChessException){
              $this->board->movePiece($possibleHideout, $kingPosition);
              $this->board->placePiece($testPiece);
              return false;
            }
          }
        }
      } catch (Exception $error) {
        if ($error instanceof ChessException){
          continue; 
        }
      }
    }
    // Récupérer les pièces attaquantes adverse pour savoir si on peut les manger ou protéger le roi
    $attacks = [];
    // Les pièce alliées aussi pour limiter les boucles
    $allies = []; 
    foreach ($this->board->getPieces() as $piece) {
      if ($piece instanceof Piece && $piece->getColor() !== $color){
        try {
          if ($piece->canMove($this->board, $kingPosition)) {
            $attacks[] = $piece;
          }
        } catch (Exception $error) {
          if ($error instanceof ChessException){
            continue; 
          }
        }
      } else if ($piece instanceof Piece && $piece->getColor() == $color) {
        $allies[]= $piece;
      }
    }
    foreach ($allies as $ally) {
      $allyBasePosition = $ally->getPosition();
      foreach ($attacks as $attack) {
        // Est-ce que l'allié peut manger la piece ? 
        try {
          if ($ally->canMove($this->board, $attack->getPosition())){
            // vérifier qu'on ne met ou ne laisse pas le roi à découvert 
            $this->board->movePiece($ally->getPosition(), $attack->getPosition());
            try{
              if(!$this->isCheck($this->currentPlayer)) {
                $this->board->movePiece($attack->getPosition(), $allyBasePosition);
                $this->board->placePiece($attack);
                return false;
              }
            }catch(Exception $error){
              $this->board->movePiece($attack->getPosition(), $allyBasePosition);
              $this->board->placePiece($attack);
              return false;
            }
          }
        } catch (Exception $error) {
        }
        if ($ally->getType() !== PieceType::KNIGHT){
          $trajectory = $this->board->trajectory($attack->getPosition(), $kingPosition);
          if (!empty($trajectory)){
            //  Pour chaque case du trajet de l'ennemi
            foreach($trajectory as $coordinates){
              $square = Position::fromKey($coordinates);
              try {
                // Si on peut se placer sur la case
                if ($ally->canMove($this->board, $square)) {
                  $this->board->movePiece($ally->getPosition(), $square);
                  try{
                    // Et que le roi n'est plus en echec
                    if(!$this->isCheck($this->currentPlayer)) {
                      $this->board->movePiece($square, $allyBasePosition);
                      $this->board->placePiece($attack);
                      return false;
                    }
                    $this->board->movePiece($square, $allyBasePosition);
                    $this->board->placePiece($attack);
                  }catch(Exception $error){
                    $this->board->movePiece($square, $allyBasePosition);
                    $this->board->placePiece($attack);
                    return false;
                  }
                }
              }catch (Exception $error){
              }
            }
          }
        }
      }
    }
    return true;
  }

  private function setupPieces(): void {
    $piecesCol =[
      [PieceType::ROOK,   [0, 7]],
      [PieceType::KNIGHT, [1, 6]],
      [PieceType::BISHOP, [2, 5]],
      [PieceType::QUEEN,  [3]],
      [PieceType::KING,   [4]],
    ];

    foreach ($piecesCol as [$type, $columns]) {
      foreach ($columns as $column) {
        $piece = $this->pieceFactory->create($type, PieceColor::WHITE, new Position(0,$column));
        $this->board->placePiece($piece);
        $piece = $this->pieceFactory->create($type, PieceColor::BLACK, new Position(7,$column));
        $this->board->placePiece($piece);
      }
    }
    for ($column = 0; $column <8; $column++){
      $piece = $this->pieceFactory->create(PieceType::PAWN, PieceColor::WHITE, new Position(1,$column));
      $this->board->placePiece($piece);
      $piece = $this->pieceFactory->create(PieceType::PAWN, PieceColor::BLACK, new Position(6,$column));
      $this->board->placePiece($piece);
      
    }
  }
  private function switchPlayer(): void {
    $this->currentPlayer = $this->currentPlayer->opposite();
  }
}