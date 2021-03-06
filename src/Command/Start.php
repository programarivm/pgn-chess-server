<?php

namespace ChessServer\Command;

use Chess\PGN\Symbol;
use ChessServer\Mode\Analysis;
use ChessServer\Mode\PlayFriend;

class Start extends AbstractCommand
{
    public function __construct()
    {
        $this->name = '/start';
        $this->description = 'Starts a new game.';
        $this->params = [
            'mode' => [
                Analysis::NAME,
                PlayFriend::NAME,
            ],
            'color' => [
                Symbol::WHITE,
                Symbol::BLACK,
            ],
            'min' => 'int',
        ];
    }

    public function validate(array $argv)
    {
        if (in_array($argv[1], $this->params['mode'])) {
            switch ($argv[1]) {
                case Analysis::NAME:
                    return count($argv) - 1 === count($this->params) - 2;
                case PlayFriend::NAME:
                    return count($argv) - 1 ===
                      count($this->params) &&
                      in_array($argv[2], $this->params['color']) &&
                      is_numeric($argv[3]);
                default:
                    // do nothing
                    break;
            }
        }

        return false;
    }
}
