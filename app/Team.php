<?php
namespace App;

use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    const TEAM_MIN_PLAYERS = 18;
    const TEAM_MAX_PLAYERS = 22;
    protected $playersSorted;
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->playersSorted = [
            'goalies' =>
                User::goalie(1)->get()->sortBy('ranking'),
            'non_goalies' =>
                User::goalie(0)->get()->sortBy('ranking')
        ];
    }

    public function getTeams(){
        $numOf = $this->getNumberOfTeamsAndPlayers();
        $teams = $this->getClosestTeams($numOf['teams'], $numOf['players']);
        return $this->calcRankAndNameForTeams($teams);
    }

    private function calcRankAndNameForTeams($teams){
        $faker = Faker::create();
        foreach($teams as $index => $team){
            $rank = 0;
            foreach($team['users'] as $user){
                $rank += $user->ranking;
            }
            $teams[$index]['rank']= $rank;
            $teams[$index]['name']= $faker->name;
        }
        return $teams;
    }

    /**
     * find the best teams in terms of ranking
     * @param $goalies
     * @param $players
     * @param $numOfTeams
     * @param $numOfPlayers
     * @return array
     */
    private function getClosestTeams($numOfTeams, $numOfPlayers){
        $teams = [];
        $increment = false;
        //give each team a goalie
        $teamsIndex = 0;
        foreach($this->playersSorted['goalies'] as $goalie){
            $teams[$teamsIndex]['users'][] = $goalie;
            if($teamsIndex === ($numOfTeams-1)){
                break;
            }
            $teamsIndex++;
        }

        $playersCount=0;
        $pauseTeamCounter = false;
        $numOfTotalPlayers = ($numOfPlayers * $numOfTeams) - $numOfTeams;
        foreach($this->playersSorted['non_goalies'] as $player){
            $teams[$teamsIndex]['users'][] = $player;
            if($playersCount ===($numOfTotalPlayers-1)) {
                break;
            }
            if(!$pauseTeamCounter) {
                if ($increment)
                    $teamsIndex++;
                else
                    $teamsIndex--;

                //when it reaches 0 change the direction for $teamsIndex to even out talent
                if ($teamsIndex === 0) {
                    $increment = true;
                    $pauseTeamCounter = true;
                }
                //when it reaches the number of teams change the direction for $teamsIndex to even out talent
                if ($teamsIndex === ($numOfTeams - 1)) {
                    $increment = false;
                    $pauseTeamCounter = true;
                }
            }
            else
                $pauseTeamCounter = false;

            $playersCount++;
        }

        return $teams;
    }

    /**
     * Return the number teams and the possible number of players for those teams
     * The number must be even each must have from TEAM_MIN_PLAYERS to TEAM_MAX_PLAYERS
     * @return array
     */
    private function getNumberOfTeamsAndPlayers(){
        //get total goalies in dataset, each team requires one
        $numOfGoalies = count($this->playersSorted['goalies']);
        //get all the players not including goalies
        $numOfPlayersNotIncludingGoalies = count($this->playersSorted['non_goalies']);
        //loop thru even numbers until the number of teams does not work
        $continueLoop = true;
        $numOfTeams = 2;
        $numOf = ['teams' =>'', 'players' =>''];
        while($continueLoop){
            //stop because each team needs a goalie
            if($numOfTeams > $numOfGoalies){
                $continueLoop = false;
            }
            //calc all the possible number of players for the current number of teams
            //loop thru from min to max players allowed
            for($players =self::TEAM_MIN_PLAYERS; $players<=self::TEAM_MAX_PLAYERS;$players++){
                //if there are enough players(not including goalies) then add to return array
                //subtract goalies from it($numOfTeams = goalies in this part of the loop)
                $playersRequiredForThisIteration = ($numOfTeams * $players);
                //if you have more or just as much players, add to the return array
                if($numOfPlayersNotIncludingGoalies >= $playersRequiredForThisIteration){
                    $numOf['teams'] = $numOfTeams;
                    $numOf['players'] = $players;
                }
            }
            if(empty($numOf['teams'])){
                $continueLoop = false;
            }
            $numOfTeams += 2;//increase by even number
        }
        return $numOf;
    }
}
