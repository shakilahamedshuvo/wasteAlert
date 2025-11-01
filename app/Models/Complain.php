<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    protected $table = 'complain';

    protected $fillable = [
        'user_id',
        'team_id',
        'complaint_type',
        'location',
        'description',
        'latitude',
        'longitude',
        'image',
        'is_recycleable',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
