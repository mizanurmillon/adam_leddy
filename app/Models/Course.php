<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
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
            'category_id' => 'integer',
        ];
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id')->where('role', 'instructor');
    }    

    public function getTotalWatchTimeAttribute()
    {
        return $this->courseWatches()->sum('watch_time');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function modules()
    {
        return $this->hasMany(CourseModule::class, 'course_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'course_tags');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function courseWatches()
    {
        return $this->hasMany(CourseWatch::class);
    }

    public function progress()
    {
        return $this->hasMany(CourseProgress::class);
    }
}
