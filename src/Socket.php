<?php

namespace PgnChessServer;

use Dotenv\Dotenv;
use PGNChess\Game;
use PgnChessServer\Command\Start;
use PgnChessServer\Command\Quit;
use PgnChessServer\Exception\ParserException;
use PgnChessServer\Mode\AiMode;
use PgnChessServer\Mode\DatabaseMode;
use PgnChessServer\Mode\PlayerMode;
use PgnChessServer\Mode\TrainingMode;
use PgnChessServer\Parser\CommandParser;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Socket implements MessageComponentInterface
{
    private $clients = [];

    private $games = [];

    private $parser;

    public function __construct()
    {
        $dotenv = new Dotenv(__DIR__.'/../');
        $dotenv->load();
        $this->parser = new CommandParser;

        echo "Welcome to PGN Chess Server" . PHP_EOL;
        echo "Commands available:" . PHP_EOL;
        echo $this->parser->cli->help() . PHP_EOL;
        echo "Listening to commands..." . PHP_EOL;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients[$conn->resourceId] = $conn;

        echo "New connection ({$conn->resourceId})" . PHP_EOL;
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $client = $this->clients[$from->resourceId];

        try {
            $cmd = $this->parser->validate($msg);
        } catch (ParserException $e) {
            $client->send(
                json_encode([
                    'message' => $e->getMessage(),
                ]) . PHP_EOL
            );
            return;
        }

        $game = $this->games[$from->resourceId]['game'] ?? null;
        $argv = $this->parser->argv;

        if ($game) {
            if (is_a($cmd, Quit::class)) {
                unset($this->games[$from->resourceId]);
                $res = [
                    'message' => 'Good bye!',
                ];
            } else {
                switch ($this->games[$from->resourceId]['mode']) {
                    case DatabaseMode::NAME:
                        $dbMode = new DatabaseMode($argv, $cmd, $game);
                        $res = $dbMode->res();
                        // TODO
                        // determine the player's turn
                        // a chess move fetched from the database will go here
                        // $dbMode->move();
                        break;
                    case TrainingMode::NAME:
                        $res = (new TrainingMode($argv, $cmd, $game))->res();
                        break;
                }
            }
        } else {
            switch (true) {
                case is_a($cmd, Start::class):
                    $this->games[$from->resourceId] = [
                        'mode' => $argv[1],
                        'game' => new Game,
                    ];
                    $res = [
                        'message' => "Game started in {$argv[1]} mode."
                    ];
                    break;
                case in_array(Start::class, $cmd->dependsOn):
                    $res = [
                        'message' => 'A game needs to be started first for this command to be allowed.',
                    ];
                    break;
            }
        }

        $client->send(json_encode($res) . PHP_EOL);
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
