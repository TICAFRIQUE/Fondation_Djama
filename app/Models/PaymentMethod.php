<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'label',
        'icon',
        'value',
        'note',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Valeur copiée dans le presse-papiers : le numéro sans espaces
    public function getCopyValueAttribute(): string
    {
        return preg_replace('/\s+/', '', $this->value);
    }
}
