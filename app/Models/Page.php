<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'content',
        'show_in_footer',
        'order',
        'is_active',
    ];

    protected $casts = [
        'show_in_footer' => 'boolean',
        'is_active' => 'boolean',
    ];
}
