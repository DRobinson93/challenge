<?php

namespace App\Http\Controllers;
use App\Team;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $team = new Team();
        return view('home')->with('teams', $team->getTeams());
    }
}
