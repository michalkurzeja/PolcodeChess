<?php

namespace Polcode\ChessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JSONResponse;

class GameController extends Controller
{
    public function createAction()
    {
        $gm = $this->get('GameMaster');
        
        $game_id = $gm->createNewGame($this->getUser());
        
        return $this->redirect( $this->generateUrl('display', array('game_id' => $game_id)), 301 );
    }
    
    public function joinAction($game_id)
    {
        return $this->redirect( $this->generateUrl('display', array('game_id' => $game_id)), 301 );
    }
    
    public function displayAction($game_id)
    {
        $gm = $this->get('GameMaster');
        
        try {
            $player_white = $gm->loadGameState( $this->getUser(), $game_id );
        } catch(NotYourGameException $e) {
            return new Response('You\'re not allowed to view this game!');
        }
        
        $cont = $gm->getAllValidMoves();
        
        return $this->render('PolcodeChessBundle:Game:game.html.twig', array(   'game_id' => $game_id, 
                                                                                'player_white' => $player_white,
                                                                                'content' => $cont));
    }
    
    public function getPiecesAction($game_id)
    {
        $gm = $this->get('GameMaster');

        try {
            $pieces = $gm->getGamePieces( $this->getuser(), $game_id );
            return new JsonResponse($pieces);
        } catch(NotYourGameException $e) {
            return new Response('Not your game!', 404);
        }
    }
}
