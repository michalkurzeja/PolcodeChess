<?php

namespace Polcode\ChessBundle\Model;

class GameUtilities
{
    public function getMovesArray($moves) {
        if( $moves ) {
            foreach($moves as $index => $square) {
                $moves[$index] = $square->toArray();
            }
        }
        
        return $moves;
    }
    
    public function getAllPiecesMovesArray($chessboard, $gm, $player_white) {
        $color = $player_white ? 'white' : 'black';
        $pieces = $chessboard->getPieces($color);
        $pieces_array = array();
        
        foreach($pieces as $piece) {
            $moves = $gm->getValidMoves($piece);
            $pieces_array[ $piece->getId() ] = $this->getPieceUpdateArray( $piece, $moves );
        }
        
        return $pieces_array;
    }
    
    public function getPieceUpdateArray($piece, $moves)
    {
        return array(
            'file' => $piece->getFile(),
            'rank' => $piece->getRank(),
            'moves' => $this->getMovesArray($moves)
        );
    }
    
    public function getPieceArray($piece, $moves)
    {              
        return array(
            'classname' => $piece->getPieceName(),
            'file' => $piece->getFile(),
            'rank' => $piece->getRank(),
            'is_white' => $piece->getIsWhite(),
            'moves' => $this->getMovesArray($moves)
        );
        
    }
    
    public function getAllPiecesArray($chessboard, $gm, $player_white)
    {
        $pieces = $chessboard->getPieces();
        $pieces_array = array();
        
        foreach($pieces as $piece) {
            $moves = ($piece->getIsWhite() == $player_white) ? $gm->getValidMoves($piece) : null;
            $pieces_array[ $piece->getId() ] = $this->getPieceArray($piece, $moves);
        }
        
        return $pieces_array;
    }
    
    public function getUserGameById($user, $game_id)
    {
        foreach($user->getAllGames() as $game) {
            if($game->getId() == $game_id) {
                return $game;
            }
        }
        
        throw new NotYourGameException();
    }
    
    
}
