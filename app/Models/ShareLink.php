<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'vault_entry_id',
        'token',
        'recipient_hash',
        'max_views',
        'views',
        'expires_at',
        'viewed_at',
    ];

    protected $casts = [
        'max_views' => 'integer',
        'views' => 'integer',
        'expires_at' => 'datetime',
        'viewed_at' => 'datetime',
    ];

    public function vaultEntry(): BelongsTo
    {
        return $this->belongsTo(VaultEntry::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isExhausted(): bool
    {
        return $this->views >= $this->max_views;
    }

    public function isConsumed(): bool
    {
        return $this->isExpired() || $this->isExhausted();
    }
}