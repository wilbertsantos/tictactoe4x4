<?php

namespace Tests\Feature;

use App\Http\Controllers\TicController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class TicControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/tic/{player?}', [TicController::class, 'startGame'])->name('tic.startGame');
        Route::get('/tic/{player}/{board}', [TicController::class, 'showBoard'])->name('tic.showBoard');
        Route::get('/tic/winner/{player}', [TicController::class, 'showWinner'])->name('tic.winner');
        Route::post('/tic/{player}/{board}/{move}', [TicController::class, 'playMove'])->name('tic.playMove');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStartGame()
    {
        $controller = new TicController();
        $response = $controller->startGame();
        $this->assertEquals('tic.game', $response->getName());
    }

    public function testShowBoard()
    {
        $controller = new TicController();
        $board = 'X--O----X';
        $player = 'X';
        $response = $controller->showBoard($player, $board);
        $this->assertEquals('tic.game', $response->getName());
        $this->assertEquals(['board' => str_split($board), 'player' => $player], $response->getData());
    }

    public function testNoMove()
    {
        $controller = new TicController();
        $board = 'XOOXOXOXO';
        $this->assertTrue($controller->noMove($board));
        $board = 'XOOXOXOX-';
        $this->assertFalse($controller->noMove($board));
    }

    public function testOpponent()
    {
        $controller = new TicController();
        $this->assertEquals('O', $controller->opponent('X'));
        $this->assertEquals('X', $controller->opponent('O'));
    }

    public function testShowWinner()
    {
        $controller = new TicController();
        $player = 'X';
        $response = $controller->showWinner($player);
        $this->assertEquals('tic.winner', $response->getName());
        $this->assertEquals(['player' => $player], $response->getData());
    }


    public function test_startGame_with_default_player()
    {
        $response = $this->get(route('tic.startGame'));
        $response->assertStatus(200);
        $response->assertViewIs('tic.game');
        $response->assertViewHas('board');
        $response->assertViewHas('player', 'X');
    }

    public function test_startGame_with_custom_player()
    {
        $response = $this->get(route('tic.startGame', ['player' => 'O']));
        $response->assertStatus(200);
        $response->assertViewIs('tic.game');
        $response->assertViewHas('board');
        $response->assertViewHas('player', 'O');
    }

    public function test_showBoard_with_moves_left()
    {
        $board = str_split('----------------');
        $response = $this->get(route('tic.showBoard', ['player' => 'X', 'board' => implode('', $board)]));
        $response->assertStatus(200);
        $response->assertViewIs('tic.game');
        $response->assertViewHas('board', $board);
        $response->assertViewHas('player', 'X');
    }

    public function test_showBoard_with_no_moves_left()
    {
        $board = str_split('XOXOXOXXOXOXOOXX');
        $response = $this->get(route('tic.showBoard', ['player' => 'X', 'board' => implode('', $board)]));
        $response->assertStatus(200);
        $response->assertViewIs('tic.tie');
    }

    public function test_noMove_with_moves_left()
    {
        $board = 'XOXOXOXXOXOXO--X';
        $this->assertFalse((new TicController())->noMove($board));
    }

    public function test_noMove_with_no_moves_left()
    {
        $board = 'XOXOXOXXOXOXOOOO';
        $this->assertTrue((new TicController())->noMove($board));
    }

    public function test_playMove_with_winner()
    {
        $player = 'X';
        $board = str_split('XO-OXXXOOXOOXO--');
        $response = $this->post(route('tic.playMove', ['player' => $player, 'board' => implode('', $board), 'position' => 8]));
        $response->assertRedirect(route('tic.winner', ['player' => $player]));
    }


}
