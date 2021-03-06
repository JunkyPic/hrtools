<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token',
        'validity',
        'from',
        'to',
        'is_valid',
    ];

    public $timestamps = true;

    public function roles() {
      return $this->hasMany(InviteRole::class, 'invite_id');
    }

}
