<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    /**
     * @var string
     */
    protected $table = 'tests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'instructions',
        'information',
        'end_test_message',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * The users that belong to the role.
     */
    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'chapter_test');
    }

}
