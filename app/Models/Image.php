<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alias', 'name',
    ];

    public $timestamps = true;

    /**
     * The users that belong to the role.
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }
}
