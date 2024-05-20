<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
    ];
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Nếu bạn có hệ thống xác thực người dùng
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}
