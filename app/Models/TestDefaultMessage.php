<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestDefaultMessage extends Model
{
    /**
     * @var string
     */
    protected $table = 'test_default_message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'default_message',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;
}
