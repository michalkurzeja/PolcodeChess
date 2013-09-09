<?php

namespace Polcode\ChessBundle\Model;

use Polcode\ChessBundle\Model\Chessboard;
use Polcode\ChessBundle\Entity\Pieces;
use Polcode\ChessBundle\Entity\Game;

class GameMaster
{
    private $game;
    private $chessboard;
    private $em;
    private $templating;
    
    public function __construct($em, $templating)
    {
        $this->em = $em;
        $this->templating = $templating;
    }
    
    public function createNewGame($user)
    {
        $this->game = new Game();
        $this->game   ->setWhite($user)
                ->setWhiteTurn(true);
        
        $this->em->persist($this->game);
        
        $this->dev_starting_pos($this->game);
        $this->em->flush();
        
        return $this->game->getId();
    }
    
    public function getGameState($user, $game_id)
    {
        $this->game = $this->getUserGameById($user, $game_id);
        
        if( !$this->game ) {
            return 'You\'re not allowed to view this game!';
        }
        
        $this->chessboard = $this->getChessboardFromDb($this->game);
        
        return $this->getValidMoves();
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
            $this->createPiece('King', 4, 1, true, $game),
            $this->createPiece('Queen', 5, 1, true, $game),
            $this->createPiece('Bishop', 3, 1, true, $game), $this->createPiece('Bishop', 6, 1, true, $game),
            $this->createPiece('Knight', 2, 1, true, $game), $this->createPiece('Knight', 7, 1, true, $game),
            $this->createPiece('Rook', 1, 1, true, $game), $this->createPiece('Rook', 8, 1, true, $game)   
        );

        $blacks = array( 
            $this->createPiece('King', 4, 8, false, $game),
            $this->createPiece('Queen', 5, 8, false, $game),
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
        
        $piece = new $full_class($file, $rank, $is_white, $game);
        
        $this->em->persist($piece);
        
        return $piece;
    }
    
    public function getValidMoves()
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
    
    public function getUserGameById($user, $game_id)
    {
        foreach($user->getAllGames() as $game) {
            if($game->getId() == $game_id) {
                return $game;
            }
        }
        
        return false;
    }
    
    public function setGame($game)
    {
        $this->game = $game;
        
        return $this;
    }
    
    public function getGameId()
    {
        return $this->game;
    }
}
