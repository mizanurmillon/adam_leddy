<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'course_module_id',
        'course_video_id',
        'finished_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'course_id' => 'integer',
            'course_module_id' => 'integer',
            'course_video_id' => 'integer',
            'finished_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relationship with CourseModule
    public function courseModule()
    {
        return $this->belongsTo(CourseModule::class);
    }

    // Relationship with CourseVideo
    public function courseVideo()
    {
        return $this->belongsTo(CourseVideo::class);
    }
}
