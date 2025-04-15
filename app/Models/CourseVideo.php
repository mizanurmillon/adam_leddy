<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseVideo extends Model
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
            'course_module_id' => 'integer',
        ];
    }

    public function courseModule()
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function courseWatches()
    {
        return $this->hasMany(CourseWatch::class);
    }

    public function progress()
    {
        return $this->hasMany(CourseProgress::class);
    }

    public function watchHistories()
    {
        return $this->hasMany(CourseWatchHistory::class);
    }
}
