<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\SubmittedChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmittedChallengeController extends Controller
{
    public function fetchChallenge($userId, $challengeId)
    {
        $challenge = SubmittedChallenge::with('challenge')->where(['user_id' => $userId, 'challenge_id' => $challengeId])->latest()->first();

        if ($challenge) {
            $data = [
                'challenge' => $challenge->challenge ?? null,
                'try' => $challenge->try,
                'status' => $challenge->status
            ];
            return response()->json($data);
        }

        $challenge = Challenge::query()->find($challengeId);
        if ($challenge) {
            $data = [
                'challenge' => $challenge,
                'try' => 0,
                'status' => 0
            ];

            return response()->json($data);
        }

        abort(404);
    }

    public function teamWiseSuccessfullySubmitted($challengeId)
    {
        $data = SubmittedChallenge::query()->with('team')
            ->where('challenge_id', $challengeId)
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get()->unique('team_id')->map(function ($val) {
                return [
                    'team' => $val->team->name ?? null,
                    'submitted_at' => $val->created_at ?? null,
                ];
            });

        return response()->json($data);

    }

    public function userWiseSuccessfullySubmitted($challengeId)
    {
        $data = SubmittedChallenge::query()
            ->with('user')
            ->where('challenge_id', $challengeId)
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get()->unique('user_id')->map(function ($val) {
                return [
                    'team' => $val->user->name ?? null,
                    'submitted_at' => $val->created_at ?? null,
                ];
            });

        return response()->json($data);
    }

    public function store(Request $request, SubmittedChallenge $submittedChallenge)
    {
        try {
            DB::beginTransaction();
            $submittedChallenge->fill($request->all());
            $submittedChallenge->try = (int)$submittedChallenge->try + 1;
            //TO DO status checking

            $submittedChallenge->save();

            return response()->json($submittedChallenge);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage());
        }
    }
}
