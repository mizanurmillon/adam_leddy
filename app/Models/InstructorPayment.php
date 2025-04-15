<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorPayment extends Model
{
    protected $fillable = ['instructor_id', 'price', 'transaction_id', 'transaction_group'];

    protected $hidden = ['updated_at'];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}
