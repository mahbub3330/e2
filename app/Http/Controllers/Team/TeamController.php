<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use function PHPUnit\Framework\exactly;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::query()->with('teamMembers')->get();

        return response()->json($teams);
    }

    public function show(Team $team)
    {
        return response()->json($team->load('teamMembers'));
    }

    public function store(Request $request, Team $team, TeamMember $teamMember)
    {
        try {
            DB::beginTransaction();
            $team->fill($request->all());
            $team->password = Hash::make($request->password);
            $team->save();

            $teamMember->team_id = $team->id;
            $teamMember->user_id = $team->created_by;
            $teamMember->save();

            DB::commit();
            return response()->json($team);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage());
        }
    }

    public function login(Request $request, TeamMember $teamMember)
    {
        $validator = $request->validate([
            'name' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        $team = Team::where('name', '=', $request->get('name'))->first();
        if (!$team) {
            return response()->json(['success' => false, 'message' => 'Login Failed, No Team exists with this name!']);
        }
        if (!Hash::check($request->get('password'), $team->password)) {
            return response()->json(['success' => false, 'message' => 'Login Failed, pls check password']);
        }


        $previouslyJoined = TeamMember::query()
            ->where(['user_id' => $request->get('user_id'), 'team_id' => $team->id])
            ->first();

        if (!$previouslyJoined) {
            $teamMember->team_id = $team->id;
            $teamMember->user_id = $request->get('user_id');
            $teamMember->save();
        }


        $response = [
            'team_id' => $previouslyJoined ? $previouslyJoined->team_id : $teamMember->team_id,
            'message' => 'Successfully LogIn',
            'status' => ResponseAlias::HTTP_ACCEPTED
        ];

        return response()->json($response);
    }


    public function destroy(Team $team)
    {
        try {
            DB::beginTransaction();
            $team->teamMembers->delete();
            $team->delete();

            DB::commit();
            return response()->json(['message' => 'Saved Successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage());
        }

    }


}
