<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateInviteInform extends Model
{
  protected $table = 'candidate_test_inform';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'email',
    'candidate_test_id',
  ];

  /**
   * @var bool
   */
  public $timestamps = true;

  public function tests() {
    return $this->belongsTo(CandidateTest::class);
  }
}
