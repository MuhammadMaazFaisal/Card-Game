<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameState extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'collected_cards'];

    protected $casts = [
        'collected_cards' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
