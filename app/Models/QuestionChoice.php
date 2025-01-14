<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionChoice extends Model
{
    use HasFactory;
    protected $fillable = ['label'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
