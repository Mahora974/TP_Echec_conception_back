<?php

namespace src;

use src\Enum\PieceColor;
use src\Enum\PieceType;
use src\Factory\PieceFactory;
use src\Board;
use src\Exception\NoPieceException;
use src\Exception\WrongTurnException;
use src\Piece\Piece;
use src\Move;

class Game {
  private Board $board;
  private PieceColor $currentPlayer;
  private PieceFactory $pieceFactory;

  public function __construct(Board $board, PieceFactory $pieceFactory) {
    $this->board = $board;
    $this->currentPlayer = PieceColor::WHITE;
    $this->pieceFactory = $pieceFactory;
  }
  public function start(): void {
    $this->setupPieces();
  }
  public function getBoard(): Board {
    return $this->board;
  }
  public function getCurrentPlayer(): PieceColor {
    return $this->currentPlayer;
  }
  public function play(Move $move): void {
    if (!$this->board->hasPieceAt($move->getFrom())){
      throw new NoPieceException();
    }
    $piece = $this->board->getPieceAt($move->getFrom());
    if ($piece->getColor() !== $this->currentPlayer){
      throw new WrongTurnException();
    }
    $this->board->movePiece($move->getFrom(), $move->getTo());
    $this->switchPlayer();
    // if ($this->isCheck($this->currentPlayer)){
    //   echo "Check";
    // }
  }
  public function isCheck(PieceColor $color): bool  {
    $kingPosition = $this->board->getKingPosition($color);
    foreach ($this->board->getPieces() as $piece) {
      if ($piece instanceof Piece && $piece->getColor() !== $this->currentPlayer){
        if ($piece->canMove($this->board, $kingPosition)){
          return true;
        }
      }
    }
    return false;
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