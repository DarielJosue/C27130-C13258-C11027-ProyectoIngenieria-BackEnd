<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id'];


    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)
            ->orderBy('created_at', 'asc');
    }


    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    /**
     * Busca o crea la conversación entre dos usuarios
     */
    public static function between(int $a, int $b): Conversation
    {

        [$u1, $u2] = $a < $b ? [$a, $b] : [$b, $a];

        return static::firstOrCreate([
            'user_one_id' => $u1,
            'user_two_id' => $u2,
        ]);
    }
}