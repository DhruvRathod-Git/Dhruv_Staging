<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;


class Todo extends Model
{
    protected $fillable = ([
        'task',
        'assign',
        'progress',
        'priority',
        'date',
        'note',
    ]);

        public function user()
    {
        return $this->belongsTo(User::class);
    }
}