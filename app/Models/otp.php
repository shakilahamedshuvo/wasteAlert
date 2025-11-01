<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class otp extends Model
{
    protected $table = 'otp';

    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at',
      
    ];
    public $timestamps = false; // 🚫 Disable timestamps
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
