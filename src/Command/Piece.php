<?php

namespace PgnChessServer\Command;

use PgnChessServer\AbstractCommand;

class Piece extends AbstractCommand
{
    public function __construct()
    {
        $this->name = '/piece';
        $this->description = 'Gets a piece by its position on the board. The "position" parameter is mandatory.';
        $this->params = [
            'position' => 'square',
        ];
        $this->dependsOn = [
            Start::class,
        ];
    }

    public function validate(array $argv)
    {
        return count($argv) - 1 === count($this->params);
    }
}
