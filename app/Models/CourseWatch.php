<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseWatch extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'course_id' => 'integer',
            'course_module_id' => 'integer',
            'course_video_id' => 'integer',
            'watch_time' => 'integer',
            'last_watched_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseModule()
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function courseVideo()
    {
        return $this->belongsTo(CourseVideo::class);
    }
}
