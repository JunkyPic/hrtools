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
        'test_name',
        'candidate_test_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    public function images() {
        return $this->hasMany(AnswerImage::class);
    }

    public function candidates() {
        return $this->belongsTo(CandidateTest::class, 'candidate_test_id');
    }

    public function reviews() {
        return $this->hasMany(Review::class, 'answer_id');
    }

}
