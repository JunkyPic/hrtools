<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    const CORRECT = 1;
    const PARTIALLY_CORRECT = 0;
    const INCORRECT = -1;
    const REQUIRES_ADDITIONAL_REVIEW = 2;

    /**
     * @var string
     */
    protected $table = 'reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_correct',
        'answer_id',
        'user_id',
        'candidate_test_id',
        'candidate_id',
        'notes',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    public function answers() {
        return $this->belongsTo(Answer::class, 'answer_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function candidateTest() {
        return $this->belongsTo(CandidateTest::class, 'id', 'user_id');
    }

    public function candidate() {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }
}
