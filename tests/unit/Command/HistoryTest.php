<?php

namespace ChessServer\Tests\Unit\Command;

use ChessServer\Command\History;
use ChessServer\Tests\Unit\CommandTestCase;

class HistoryTest extends CommandTestCase
{
    /**
     * @test
     */
    public function validate_history()
    {
        $this->assertInstanceOf(
            History::class,
            self::$parser->validate('/history')
        );
    }

    /**
     * @test
     * @expectedException ChessServer\Exception\ParserException
     */
    public function validate_history_foo()
    {
        self::$parser->validate('/history foo');
    }
}
