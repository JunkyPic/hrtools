<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditableArea extends Model
{

  protected $table = 'editable_area';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'header',
    'instructions',
    'footer',
    'test_id',
  ];

  public $timestamps = true;

  public function test() {
    return $this->belongsTo(Test::class);
  }
}
