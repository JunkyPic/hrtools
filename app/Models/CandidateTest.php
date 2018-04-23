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
        'finished_at',
        'candidate_id',
        'test_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers() {
        return $this->hasMany(Answer::class, 'candidate_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function candidate() {
        return $this->belongsto(Candidate::class, 'candidate_id');
    }
}
