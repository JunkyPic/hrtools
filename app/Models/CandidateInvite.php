<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateInvite extends Model
{
    /**
     * @var string
     */
    protected $table = 'candidate_invite';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_token',
        'test_token',
        'to_email',
        'to_fullname',
        'from',
        'test_id',
        'test_validity',
        'invite_validity',
        'is_email_token_valid',
        'is_invite_token_valid',
        'test_started_at',
        'test_finished_at',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;
}
