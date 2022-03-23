<?php

namespace App\Http\Controllers\Challenge;

use App\Http\Controllers\Controller;
use App\Models\ChallengeType;
use Illuminate\Http\Request;
use function response;

class ChallengeTypeController extends Controller
{
    public function index()
    {
        $challengeTypes = ChallengeType::query()->get();
        return response()->json($challengeTypes);
    }

    public function show(ChallengeType $challengeType)
    {
        return response()->json($challengeType);
    }

    public function store(ChallengeType $challengeType, Request $request)
    {
        try {
            $challengeType->fill($request->all())->save();
            return response()->json(['message' => 'Saved Successfully']);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function update(ChallengeType $challengeType, Request $request)
    {
        try {
            $challengeType->fill($request->all())->update();
            return response()->json(['message' => 'Saved Successfully']);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function destroy(ChallengeType $challengeType)
    {
        try {
            $challengeType->delete();
            return response()->json(['message' => 'Saved Successfully']);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
