<?php

namespace src;

use src\Enum\PieceColor;
use src\Enum\PieceType;
use src\Factory\PieceFactory;
use src\Board;
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
  protected null|Position $canBeEatenInPassing = null;

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
  public function play(Move $move): string | null {
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

    } else if ($piece->canMove($this->board, $move->getTo())) {
      $this->board->movePiece($move->getFrom(), $move->getTo());
      // vérifier qu'on ne met ou ne laisse pas le roi à découvert 
      if ($this->isCheck($this->currentPlayer)) {
        $this->board->movePiece($move->getTo(), $move->getFrom());
        throw new InvalidMoveException("You are checked");
      }
      if ($piece->getType() == PieceType::PAWN && abs($move->getFrom()->getRow() - $move->getTo()->getRow()) == 2){
        $this->canBeEatenInPassing = $piece->getPosition();
        $this->board->ghostPawn(new Position($move->getTo()->getRow() + ($move->getFrom()->getRow() - $move->getTo()->getRow())/2, $move->getTo()->getColumn()));
      } else if (!is_null($this->canBeEatenInPassing)){
        $this->canBeEatenInPassing = null;
        $this->board->clearGhostPawn();
      }
    } else {
      throw new InvalidMoveException();
    }
    $this->switchPlayer();
    if ($this->isCheck($this->currentPlayer)){
      return "CHECK";
    }
    return null;
  }
  public function isCheck(PieceColor $color): bool  {
    $kingPosition = $this->board->getKingPosition($color);
    foreach ($this->board->getPieces() as $piece) {
      if ($piece instanceof Piece && $piece->getColor() !== $color){
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