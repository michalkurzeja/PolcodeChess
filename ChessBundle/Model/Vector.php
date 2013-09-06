<?php

namespace Polcode\ChessBundle\Model;

class Vector
{
    private $x;
    
    private $y;
    
    public function __construct($x, $y, $invert = false)
    {
        $factor = $invert ? -1 : 1;
        $this->setCoordinates($x * $factor, $y * $factor);
    }
    
    public function multiplyByScalar($val)
    {
        $this->setX($this->getX() * $val);
        $this->sety($this->gety() * $val);
    }
    
    public function setCoordinates($x, $y) {
        $this->setX($x);
        $this->setY($y);
    }
    
    public function getCoordinates()
    {
        return array( $this->getX(), $this->getY() );
    }
    
    public function setX($val)
    {
        $this->x = $x;
        
        return $this;
    }
    
    public function setY($val)
    {
        $this->y = $y;
        
        return $this;
    }
    
    public function getX()
    {
        return $this->x;
    }
    
    public function getY()
    {
        return $this->y;
    }
}
