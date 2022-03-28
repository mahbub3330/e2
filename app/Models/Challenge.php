<?php

namespace App\Models;

use App\Casts\Json;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $casts = [
        'flag' => Json::class,
    ];
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'question',
        'image',
        'slug',
        'type',
        'flag',
        'score',
    ];
    public function category()
    {
        return $this->belongsTo(ChallengeType::class, 'category_id')->withDefault();
    }
}
