<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
    protected $fillable = [
        'email',
        'code',
        'activated',
        'state',
        'expiration',
    ];

    public function isExpired()
    {
        $this->state = $this->state ? $this->expiration && $this->expiration < now() : false;
        return !$this->state;
    }
}
