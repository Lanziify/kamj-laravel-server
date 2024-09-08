<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id', 'title', 'description', 'content'];

    public function lessons()
    {
        return $this->belongsTo(Lesson::class);
    }
    public function quiz()
    {
        return $this->hasMany(Quiz::class);
    }
}
