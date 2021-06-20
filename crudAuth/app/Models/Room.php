<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'roomId';

    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'room','details'
    ];

    /**
     * One to many relationship
     * Relation to student model
     */

    public function student()
    {
        return $this->hasMany('App\Model\Student');
    }
}
