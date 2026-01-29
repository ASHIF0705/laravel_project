<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypingResult extends Model
{
    protected $fillable = [
    'username','wpm','accuracy','time_selected'
];

}
