<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    const CORRECT = 1;
    const PARTIALLY_CORRECT = 0;
    const INCORRECT = -1;
    const REQUIRES_ADDITIONAL_REVIEW = 2;

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
        'test_id',
        'test_name',
        'candidate_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function candidates() {
        return $this->belongsto(CandidateTest::class, 'candidate_id');
    }
}
