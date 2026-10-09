<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FlashInfo extends Model
{
    public const TYPES = [
        'info' => 'Information',
        'important' => 'Important',
        'urgent' => 'Urgent',
    ];

    protected $fillable = [
        'message',
        'link_text',
        'link_url',
        'type',
        'starts_at',
        'ends_at',
        'order',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Infos actives et dans leur période de diffusion
    public function scopeCurrent(Builder $query): Builder
    {
        $now = now();

        return $query->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn (Builder $q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->orderBy('order')
            ->orderBy('id');
    }

    // programmée | en ligne | expirée | désactivée
    public function getStatusAttribute(): string
    {
        if (! $this->is_active) {
            return 'désactivée';
        }
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return 'programmée';
        }
        if ($this->ends_at && $this->ends_at->isPast()) {
            return 'expirée';
        }

        return 'en ligne';
    }
}
