<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * @var string
     */
    protected $table = 'answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_title',
        'question_body',
        'answer',
        'is_correct',
        'comment',
        'question_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;
}
