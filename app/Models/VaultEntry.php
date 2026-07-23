<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VaultEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'value',
        'title',
        'site',
        'username',
        'notes',
        'type',
        'length',
        'include_special',
        'include_numbers',
        'include_uppercase',
        'include_lowercase',
        'entropy_bits',
    ];

    protected $casts = [
        'include_special' => 'boolean',
        'include_numbers' => 'boolean',
        'include_uppercase' => 'boolean',
        'include_lowercase' => 'boolean',
        'length' => 'integer',
        'entropy_bits' => 'integer',
    ];

    protected $hidden = [
        'value',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function shareLinks(): HasMany
    {
        return $this->hasMany(ShareLink::class);
    }

    public function getDecryptedValue(): string
    {
        return decrypt($this->value);
    }
}