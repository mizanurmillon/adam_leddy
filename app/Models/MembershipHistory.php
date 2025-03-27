<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipHistory extends Model
{
    protected $fillable = ['user_id', 'subscription_id', 'start_date', 'end_date', 'price', 'type'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the user that owns the membership.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription associated with the membership.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
