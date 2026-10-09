<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'status',
        'progress',
        'date_start',
        'date_end',
        'order'
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    // Libellés et couleurs des statuts proposés dans l'admin
    public const STATUSES = [
        'en_cours' => ['label' => 'En cours', 'bg' => '#E8F5E9', 'color' => '#2E7D32'],
        'financement' => ['label' => 'En financement', 'bg' => '#FFF8E1', 'color' => '#B26A00'],
        'bientot' => ['label' => 'Bientôt', 'bg' => '#E3F2FD', 'color' => '#1565C0'],
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? ucfirst(str_replace('_', ' ', (string) $this->status));
    }

    public function getStatusStyleAttribute(): string
    {
        $status = self::STATUSES[$this->status] ?? self::STATUSES['bientot'];

        return "background:{$status['bg']};color:{$status['color']};";
    }
}
