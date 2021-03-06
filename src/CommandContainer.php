<?php

namespace ChessServer;

use ChessServer\Command\AcceptFriendRequest;
use ChessServer\Command\Ascii;
use ChessServer\Command\Castling;
use ChessServer\Command\Captures;
use ChessServer\Command\Fen;
use ChessServer\Command\History;
use ChessServer\Command\IsCheck;
use ChessServer\Command\IsMate;
use ChessServer\Command\Piece;
use ChessServer\Command\Pieces;
use ChessServer\Command\PlayFen;
use ChessServer\Command\Quit;
use ChessServer\Command\Start;
use ChessServer\Command\Status;

class CommandContainer
{
    private $obj;

    public function __construct()
    {
        $this->obj = new \SplObjectStorage;
        $this->obj->attach(new AcceptFriendRequest());
        $this->obj->attach(new Ascii());
        $this->obj->attach(new Castling());
        $this->obj->attach(new Captures());
        $this->obj->attach(new Fen());
        $this->obj->attach(new History());
        $this->obj->attach(new IsCheck());
        $this->obj->attach(new IsMate());
        $this->obj->attach(new Piece());
        $this->obj->attach(new Pieces());
        $this->obj->attach(new PlayFen());
        $this->obj->attach(new Quit());
        $this->obj->attach(new Start());
        $this->obj->attach(new Status());
    }

    public function findByName(string $name)
    {
        $this->obj->rewind();
        while ($this->obj->valid()) {
            if ($this->obj->current()->name === $name) {
                return $this->obj->current();
            }
            $this->obj->next();
        }

        return null;
    }

    public function help()
    {
        $o = '';
        $this->obj->rewind();
        while ($this->obj->valid()) {
            $o .= $this->obj->current()->name;
            $this->obj->current()->params ? $o .= ' ' . json_encode($this->obj->current()->params) : null;
            $o .= ' ' . $this->obj->current()->description . PHP_EOL;
            $this->obj->next();
        }

        return $o;
    }
}
