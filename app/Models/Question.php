<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body',
    ];


    /**
     * The users that belong to the role.
     */
    public function images()
    {
        return $this->belongsToMany(Image::class);
    }
}
