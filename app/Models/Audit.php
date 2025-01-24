<?php

namespace App\Models;

use App\Enums\ActionAudit;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = 'date';
    protected $fillable = [
        'user_id',
        'action',
        'auditable_id',
        'auditable_type',
        'details',
        'ip',
        'browser',
    ];
    protected $casts = [
        'action' => ActionAudit::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(){
        return $this->morphTo();
    }
}
