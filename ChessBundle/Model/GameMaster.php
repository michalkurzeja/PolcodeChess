<?php

namespace Polcode\ChessBundle\Model;

use Polcode\ChessBundle\Model\Chessboard;
use Polcode\ChessBundle\Entity\Pieces;
use Polcode\ChessBundle\Entity\Game;
use Polcode\ChessBundle\Exception\NotYourGameException;
use Polcode\ChessBundle\Exception\InvalidClassNameException;
use Polcode\ChessBundle\Exception\InvalidMoveException;
use Polcode\ChessBundle\Exception\GameFullException;

class GameMaster
{
    private $game;
    private $chessboard;
    private $em;
    private $game_utils;
    
    public function __construct($em, $game_utils)
    {
        $this->em = $em;
        $this->game_utils = $game_utils;
    }
    
    
    
    public function createNewGame($user)
    {
        $this->game = new Game();
        $this->game  ->setWhite($user)
                     ->setWhiteTurn(true);
        
        $this->em->persist($this->game);
        
        $this->generateStartingPositions($this->game);
        $this->em->flush();
        
        return $this->game->getId();
    }
    
    public function joinGame($user, $game_id) {
        try {
            $this->game = $this->game_utils->getGameWithSlot($game_id, $this->em);
        } catch(GameFullException $e) {
            throw $e;
        }
        
        $this->game->setPlayerOnEmptySlot($user);
        $this->em->flush();
    }
    
    public function getGamePieces($user, $game_id)
    {
        try {
            $player_white = $this->loadGameState($user, $game_id);
        } catch(NotYourGameException $e) {
            throw $e;
        }        
        
        return $this->game_utils->getAllPiecesArray( $this->chessboard, $this, $player_white );       
    }
    
    public function loadGameState($user, $game_id)
    {
        try {
            $this->game = $this->game_utils->getUserGameById($user, $game_id);        
        } catch(NotYourGameException $e) {
            throw $e ;
        }
                
        $this->chessboard = $this->getChessboardFromDb($this->game);
        
        return $this->game->isPlayerWhite($user);
    }
    
    public function getChessboardFromDb($game)
    {
        return new Chessboard($game->getWhitePieces()->getValues(), $game->getBlackPieces()->getValues());
    }
    
    public function dev_starting_pos($game)
    {
        $whites = array(    $this->createPiece('Queen', 5, 1, true, $game),
                            $this->createPiece('Pawn', 1, 2, true, $game) );
        $blacks = array(    $this->createPiece('Queen', 5, 8, false, $game),
                            $this->createPiece('Pawn', 1, 7, false, $game) );
                            
        $this->chessboard = new Chessboard($whites, $blacks);
    }
    
    public function generateStartingPositions($game)
    {
        $whites = array( 
            $this->createPiece('King', 5, 1, true, $game),
            $this->createPiece('Queen', 4, 1, true, $game),
            $this->createPiece('Bishop', 3, 1, true, $game), $this->createPiece('Bishop', 6, 1, true, $game),
            $this->createPiece('Knight', 2, 1, true, $game), $this->createPiece('Knight', 7, 1, true, $game),
            $this->createPiece('Rook', 1, 1, true, $game), $this->createPiece('Rook', 8, 1, true, $game)   
        );

        $blacks = array( 
            $this->createPiece('King', 5, 8, false, $game),
            $this->createPiece('Queen', 4, 8, false, $game),
            $this->createPiece('Bishop', 3, 8, false, $game), $this->createPiece('Bishop', 6, 8, false, $game),
            $this->createPiece('Knight', 2, 8, false, $game), $this->createPiece('Knight', 7, 8, false, $game),
            $this->createPiece('Rook', 1, 8, false, $game), $this->createPiece('Rook', 8, 8, false, $game)   
        );

        for($i=1; $i<=8; $i++) {
            $whites[] =  $this->createPiece('Pawn', $i, 2, true, $game);
            $blacks[] =  $this->createPiece('Pawn', $i, 7, false, $game);
        }

        $this->chessboard = new Chessboard($whites, $blacks);
    }
    
    public function createPiece($class, $file, $rank, $is_white, $game)
    {
        
        $full_class = "Polcode\\ChessBundle\\Entity\\Pieces\\$class";
        
        try {
            $piece = new $full_class($file, $rank, $is_white, $game);
        } catch(InvalidClassNameException $e) {} /* do something? */
        
        $this->em->persist($piece);
        
        return $piece;
    }
    
    public function isMyTurn($user)
    {
        if( $this->game->getWhiteTurn() === $this->game->isPlayerWhite($user) ) {
            return true;
        }
        
        return false;
    }
    
    public function getUpdate($user, $game_id, $move_count) {
        $update = array();
        
        try {
            $player_white = $this->loadGameState($user, $game_id);
        } catch(NotYourGameException $e) {
            throw $e;
        }
        
        if( $this->getMoveCount() > $move_count ) {
            /* get update on player's turn */
            $update['turn'] = $this->isMyTurn($user);

            $update['old_move_count'] = $move_count;
            /* get current move count */
            $update['move_count'] = $this->getMoveCount();
                        
            /* get last moved piece */
            $update['last_moved'] = $this->game_utils->getLastMovedPieceArray( $this->game->getLastMoved() );
            
            /* get update moves of pieces */
            $update['moves'] = $this->game_utils->getAllPiecesMovesArray($this->chessboard, $this, $player_white);
        }

        return $update;
    }
    
    public function movePiece($user, $game_id, $data) {
         try {
            $player_white = $this->loadGameState($user, $game_id);
        } catch(NotYourGameException $e) {
            throw $e;
        }
        
        /* set no-ones turn for the time of calculations */
        $this->game->setWhiteTurn(null);
        $this->em->flush();
        
        $piece = $this->chessboard->findPieceById($data->piece->id);
        
        if( !$this->game_utils->verifyPiece($piece, $data->piece, $player_white) ) {
            throw new InvalidMoveException();
        }
        
        if( !$this->isMoveLegal($piece, $data->coords) ) {
            throw new InvalidMoveException();
        }
        
        $piece->setCoordinates( new Vector( $data->coords->file, $data->coords->rank ) ); /* set new coordinates for a piece */
        $this->game->setLastMoved($piece); /* sets the last moved piece */
        $this->game->incrementMoveCount(); /* increment move count */
        $this->game->setWhiteTurn( !$player_white ); /* if player is white, sets turn to black (and vice versa) */
        
        $this->em->flush();
    }
        
    public function isMoveLegal($piece, $coords)
    {
        $legal_moves = $this->getValidMoves($piece);
        
        foreach($legal_moves as $square) {
            if( $square->getX() == $coords->file && $square->getY() == $coords->rank ) {
                return true;
            }
        }
        
        return false;
    }
    
    public function getValidMoves($piece)
    {
        $squares = $this->chessboard->getPieceMoveList($piece);
        
        return $squares;
    }
    
    public function getAllValidMoves()
    {
        $pieces = $this->chessboard->getPieces();
        
        $positions = '';
        
        foreach($pieces as &$piece) {
            $positions .= "{$piece}".PHP_EOL;
            $squares = $this->chessboard->getPieceMoveList($piece);
              foreach($squares as &$square) {
                  $positions .= "\t{$square}" . PHP_EOL;
              }
        }
        
        return $positions;
    }
    
    public function setGame($game)
    {
        $this->game = $game;
        
        return $this;
    }
    
    public function getGameId()
    {
        return $this->game->getId();
    }
    
    public function getMoveCount()
    {
        return $this->game->getMoveCount();
    }
}
