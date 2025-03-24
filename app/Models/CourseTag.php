<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTag extends Model
{
    
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function casts()
    {
        return [
            'id' => 'integer',
            'course_id' => 'integer',
            'tag_id' => 'integer',
        ];
    }
}
