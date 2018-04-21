<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Chapter
 *
 * @package App\Models
 */
class Chapter extends Model
{
    /**
     * @var string
     */
    protected $table = 'chapters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chapter',
        'information',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * The users that belong to the role.
     */
    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }

    /**
     * The users that belong to the role.
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'chapter_question');
    }
}
