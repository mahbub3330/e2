<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedChallenge extends Model
{
    use HasFactory;

    protected $table = 'submitted_challenge';

    protected $fillable = [
        'challenge_id',
        'team_id',
        'user_id',
        'score',
        'try',
        'flag',
        'status',
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'challenge_id')->withDefault();
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
