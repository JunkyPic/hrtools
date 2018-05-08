<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateTest extends Model
{
    /**
     * @var string
     */
    protected $table = 'candidate_test';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token',
        'is_valid',
        'validity',
        'started_at',
        'phone',
        'finished_at',
        'candidate_id',
        'test_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    public function answers() {
        return $this->hasMany(Answer::class, 'candidate_test_id');
    }

    public function informs() {
        return $this->hasMany(CandidateInviteInform::class, 'candidate_test_id');
    }

    public function candidate() {
        return $this->belongsto(Candidate::class, 'candidate_id');
    }

    public function reviews() {
        return $this->hasMany(Review::class, 'candidate_test_id', 'id');
    }
}
