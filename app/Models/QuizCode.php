<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class QuizCode extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'expires_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = self::generateCode($model);
            if (!$model->expires_at) {
                $model->expires_at = Carbon::now()->addDays(7);
            }
        });
    }

    public static function generateCode($model)
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (self::where('code', $model->code)->exists());

        return $code;
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
