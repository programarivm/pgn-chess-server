## PHP Chess Server

PHP Ratchet WebSocket server using [PHP Chess](https://github.com/programarivm/php-chess). The chess server is intended to connect to a [Redux Chess](https://github.com/programarivm/redux-chess) app.

### WebSocket Server

Create an `.env` file:

    $ cp .env.example .env

Start the server:

	$ php cli/ws-server.php
    Welcome to PHP Chess Server
    Commands available:
    /accept {"id":"id"} Accepts a friend request to play a game.
    /ascii Prints the ASCII representation of the game.
    /castling Gets the castling status.
    /captures Gets the pieces captured by both players.
    /fen Prints the FEN string representation of the game.
    /history The current game's history.
    /ischeck Finds out if the game is in check.
    /ismate Finds out if the game is over.
    /piece {"position":"string"} Gets a piece by its position on the board.
    /pieces {"color":["w","b"]} Gets the pieces on the board by color.
    /playfen {"fen":"string"} Plays a chess move in shortened FEN format.
    /quit Quits a game.
    /start {"mode":["analysis","playfriend"],"color":["w","b"],"min":"int"} Starts a new game.
    /status The current game status.

    Listening to commands...


Open a console in your favorite browser and run commands:

    const ws = new WebSocket('ws://127.0.0.1:8080');
    ws.onmessage = (res) => { console.log(res.data) };
    ws.send('/start analysis');

### License

The MIT License.

### Contributions

Would you help make this app better?

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "PHP Chess Server"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)

Thank you.
