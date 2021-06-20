<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'detail', 'userId'
    ];

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'userId');
    }
}
