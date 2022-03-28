<?php

namespace App\Http\Services;

use App\Models\Challenge;

class ChallengeFormatService
{
    public static function formatChallenges()
    {
        $challenges = Challenge::query()
            ->with('category')
            ->get()
            ->map(function ($ch){
                return [
                    'id' => $ch->id ?? null,
                    'category' => $ch->category->name ?? null,
                    'color' => $ch->category->color ?? null,
                    'score' => $ch->score ?? 0
                ];
            })
        ;
        return $challenges;
    }

}
