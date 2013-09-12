<?php

namespace Polcode\ChessBundle\Model;

use Polcode\ChessBundle\Model\Chessboard;
use Polcode\ChessBundle\Entity\Pieces;
use Polcode\ChessBundle\Entity\Game;
use Polcode\ChessBundle\Exception\NotYourGameException;
use Polcode\ChessBundle\Exception\InvalidClassNameException;
use Polcode\ChessBundle\Exception\InvalidMoveException;

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
    
    public function getUpdate($user, $game_id) {
        $update = array();
        
        try {
            $is_white = $this->loadGameState($user, $game_id);
        } catch(NotYourGameException $e) {
            throw $e;
        }
        
        if($this->game->getWhiteTurn() === $is_white) {
            $update['turn'] = true;
        } else {
            $update['turn'] = false;
        }
        
        return $update;
    }
    
    public function movePiece($user, $game_id, $data) {
         try {
            $is_white = $this->loadGameState($user, $game_id);
        } catch(NotYourGameException $e) {
            throw $e;
        }
        
        $piece = $this->chessboard->findPieceById($data->piece->id);
        
        if( !$this->verifyPiece($piece, $data->piece, $is_white) ) {
            throw new InvalidMoveException();
        }
        
        if( !$this->isMoveLegal($piece, $data->coords) ) {
            throw new InvalidMoveException();
        }
        
        $piece->setCoordinates( new Vector( $data->coords->file, $data->coords->rank ) );
        $this->em->flush();
        $this->chessboard = $this->getChessboardFromDb($this->game);
        return $this->game_utils->getAllPiecesMovesArray( $this->chessboard, $this, $is_white );
    }
    
    public function verifyPiece($piece, $position, $owner_white) {
        if( $piece->getFile() == $position->file 
            && $piece->getRank() == $position->rank
            && $piece->getIsWhite() == $owner_white ) {
                
            return true;
        }
        
        return false;
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
}
