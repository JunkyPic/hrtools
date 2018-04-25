<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteRole extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'invite_id',
    'role_name',
  ];

  public $timestamps = true;

  public function invite() {
    return $this->belongsTo(Invite::class);
  }

}
