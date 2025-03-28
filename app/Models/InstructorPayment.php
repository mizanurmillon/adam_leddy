<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorPayment extends Model
{
    protected $fillable = ['instructor_id', 'price'];

    protected $hidden = ['updated_at'];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}
