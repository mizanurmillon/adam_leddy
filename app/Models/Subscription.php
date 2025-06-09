<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function membershipHistories()
    {
        return $this->hasMany(MembershipHistory::class);
    }
}
