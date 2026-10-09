<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Site;

class Realisation extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'date_start',
        'date_end',
        'order'
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Site::uniqueSlug(static::class, $model->title);
        });
    }
}
