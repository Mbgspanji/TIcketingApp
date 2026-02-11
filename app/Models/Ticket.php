<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'open',
        'priority' => 'medium',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }


    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'open' => 'bg-warning',
            'in_progress' => 'bg-info',
            'closed' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'low' => 'bg-secondary',
            'medium' => 'bg-primary',
            'high' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
