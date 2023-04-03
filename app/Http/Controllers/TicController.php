<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

class TicController extends Controller
{
    // Start the game with a specified player, default is 'X'
    public function startGame($player = 'X')
    {
        $board = str_split('----------------');
        return view('tic.game', ['board' => $board, 'player' => $player]);
    }

    // Display the game board with the current player
    public function showBoard($player, $board)
    {
        // Check if the no more tile to play
        if ($this->noMove($board)) {
            return view('tic.tie');
        }
        $board = str_split($board);
        return view('tic.game', ['board' => $board, 'player' => $player]);
    }

    // Start the game with a specified player, default is 'X'
    public function noMove($board)
    {
        return !Str::contains($board, '-');
    }
    // Play a move on the game board
    public function playMove($player, $board, $move)
    {   
        $board = str_split($board);
        $board[$move] = $player;
        
        // Check if the player has won
        if ($this->isWinner($board, $player)) {
            return redirect()->route('tic.winner', ['player' => $player]);
        }

        // Play the opponent's move
        $opponent = $this->opponent($player);
        $board = $this->autoMove($board, $opponent);

        // Check if the opponent has won
        if ($this->isWinner($board, $opponent)) {
            return redirect()->route('tic.winner', ['player' => $opponent]);
        }

        // Display the updated board
        return redirect()->route('tic.showBoard', ['board' => implode('', $board), 'player' => $player]);
    }

    // Get the opponent player
    public function opponent($player)
    {
        return ($player === 'X') ? 'O' : 'X';
    }

    // Display the winner of the game
    public function showWinner($player)
    {
        return view('tic.winner', ['player' => $player]);
    }

    // Play an automatic move for the opponent player
    public function autoMove($board, $player)
    {
        // Try to find a winning move for the player
        $winningMove = $this->findWinningMove($board, $player);
        if ($winningMove >= 0) {
            $board[$winningMove] = $player;
            return $board;
        }

        // Try to find a blocking move for the opponent
        $opponent = $this->opponent($player);
        $blockingMove = $this->findWinningMove($board, $opponent);
        if ($blockingMove >= 0) {
            $board[$blockingMove] = $player;
            return $board;
        }

        // Play a random move if no winning or blocking move is available
        if (array_key_exists('-', array_count_values($board))) {
            $numEmpty = array_count_values($board)['-'];
        } else {
            $numEmpty = 0;
        }

        $pos = rand(0, $numEmpty - 1);
        $cursor = 0;
        foreach ($board as $i => $cell) {
            if ($cell === '-') {
                if ($cursor === $pos) {
                    $board[$i] = $player;
                    return $board;
                }
                $cursor += 1;
            }
        }
        return $board;
    }

    function isWinner($board, $player)
    {
        // Define all the possible winning combinations
        $winningCombos = array(
            array(0, 1, 2, 3), array(4, 5, 6, 7), array(8, 9, 10, 11), array(12, 13, 14, 15), // horizontal
            array(0, 4, 8, 12), array(1, 5, 9, 13), array(2, 6, 10, 14), array(3, 7, 11, 15), // vertical
            array(0, 5, 10, 15), array(3, 6, 9, 12) // diagonal
        );

        // Check each winning combination
        foreach ($winningCombos as $combo) {
            $win = true;
            foreach ($combo as $pos) {
                if ($board[$pos] !== $player) {
                    $win = false;
                    break;
                }
            }
            if ($win) {
                return true;
            }
        }

        // No winning combination found
        return false;
    }

    public function findWinningMove(array $board, string $player): int
    {
        foreach ($board as $position => $value) {
            if ($value === "-") {
                $board[$position] = $player;
                if ($this->isWinner($board, $player)) {
                    return $position;
                }
                $board[$position] = '-';
            }
        }
        return -1;
    }
}