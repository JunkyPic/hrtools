<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerImage extends Model
{
    /**
     * @var string
     */
    protected $table = 'answer_image';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alias',
        'folder',
        'answer_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    public function answers() {
        return $this->belongsTo(Answer::class);
    }
}
