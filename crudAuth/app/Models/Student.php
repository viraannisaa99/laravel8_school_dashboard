<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'name', 'nim', 'phone', 'email', 'roomId', 'photo'
    ];

    /**
     * One to many relationship
     * Relation to room model
     */

    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'roomId');
    }
}
