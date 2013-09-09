<?php

namespace Polcode\ChessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
        
        $cont = $gm->getGameState( $this->getUser(), $game_id );
        
        return $this->render('PolcodeChessBundle:Game:game.html.twig', array('content' => $cont));
    }
}
