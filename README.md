# TP_Echec_conception_back
TP du cours conception et développement backend de M1 Fullstack de MDS

## Classes principales

- ✅  `Position`
  - ✅  `__construct()`
  - ✅  `getRow()`
  - ✅  `getColumn()`
  - ✅  `equals()`
  - ✅  `toKey()`
  - ✅  `fromKey()`

- ✅  `Move`
  - ✅  `__construct()`
  - ✅  `getFrom()`
  - ✅  `getTo()`

- ✅  `Board`
  - ✅  `placePiece()`
  - ✅  `getPieceAt()`
  - ✅  `hasPieceAt()`
  - ✅  `removePieceAt()`
  - ✅  `movePiece()`
  - ✅  `isPathClear()`
  - ✅  `getPieces()`
  - ✅  `getKingPosition()`
  - ✅  `render()`

- ✅  `Game`
  - ✅  `__construct()`
  - ✅  `start()`
  - ✅  `getBoard()`
  - ✅  `getCurrentPlayer()`
  - ✅  `play()`
  - ✅  `isCheck()`
  - ✅  `setupPieces()`
  - ✅  `switchPlayer()`

## Pièces

- ✅  `Piece`
  - ✅  `__construct()`
  - ✅  `getColor()`
  - ✅  `getPosition()`
  - ✅  `setPosition()`
  - ✅  `getType()`
  - ✅  `render()`
  - ✅  `canMove()`
  - ✅  `isValidMovementShape()`
  - ✅  `canCapture()`

- ✅  `King`
  - ✅  `isValidMovementShape()`

- ✅  `Queen`
  - ✅  `isValidMovementShape()`

- ✅  `Rook`
  - ✅  `isValidMovementShape()`

- ✅  `Bishop`
  - ✅  `isValidMovementShape()`

- ✅  `Knight`
  - ✅  `isValidMovementShape()`

- ✅  `Pawn`
  - ✅  `isValidMovementShape()`

## Factory

- ✅  `PieceFactory`
  - ✅  `create()`

## Interface / Enums

- ✅  `Renderable`
  - ✅  `render()`

- ✅  `PieceColor`
  - ✅  `WHITE`
  - ✅  `BLACK`
  - ✅  `opposite()`

- ✅  `PieceType`
  - ✅  `KING`
  - ✅  `QUEEN`
  - ✅  `ROOK`
  - ✅  `BISHOP`
  - ✅  `KNIGHT`
  - ✅  `PAWN`

## Exceptions

- ✅  `ChessException`
- ✅  `InvalidMoveException`
- ✅  `NoPieceException`
- ✅  `WrongTurnException`
- ✅  `OccupiedByAllyException`

## Bonus

- ✅ Roque
- ✅ Promotion du pion
- ✅ Prise en passant
- ✅ Interdiction de mettre son propre roi en échec
- ✅ Échec et mat
- ❌ Pat
- ❌ Historique complet des coups
- ✅ Tests automatisés (8 fct°/ 2 classes)
- ❌ Autre bonus : `à préciser`