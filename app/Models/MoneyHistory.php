<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoneyHistory extends Model
{
    protected $table = 'money_history';
    
    protected $fillable = [
        'user_id',
        'money',
        'description',
        'type'
    ];

    protected $casts = [
        'money' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
