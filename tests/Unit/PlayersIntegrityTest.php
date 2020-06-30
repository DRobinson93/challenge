<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\Team;

class PlayersIntegrityTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGoaliePlayersExist ()
    {
/*
		Check there are players that have can_play_goalie set as 1
*/
		$result = User::where('user_type', 'player')->where('can_play_goalie', 1)->count();
		$this->assertTrue($result > 1);

    }
    public function testAtLeastOneGoaliePlayerPerTeam ()
    {
        /*
                calculate how many teams can be made so that there is an even number of teams and they each have between 18-22 players.
                Then check that there are at least as many players who can play goalie as there are teams
        */
        $team = new Team();
        $teams = $team->getTeams();
        $this->assertTrue(count($teams) % 2 === 0);//even number of teams
        foreach($teams as $t) {
            $this->assertArrayHasKey('rank', $t);
            $this->assertArrayHasKey('users', $t);
            $numOfPlayers = count($t['users']);
            $this->assertTrue($numOfPlayers >= Team::TEAM_MIN_PLAYERS && $numOfPlayers <= Team::TEAM_MAX_PLAYERS);
            $canPlayGoalieCol = array_column($t['users'], 'can_play_goalie');
            //one and only one goalie exists
            $this->assertTrue(array_sum($canPlayGoalieCol) === 1);
            $this->assertArrayHasKey('name', $t);
        }

    }
}
