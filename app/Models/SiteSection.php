<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SiteSection extends Model
{
    protected $fillable = [
        'key',
        'eyebrow',
        'title',
        'subtitle',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Sections connues du site (config/site.php), fusionnées avec ce que l'admin a enregistré
    public static function resolved(): Collection
    {
        $rows = static::all()->keyBy('key');
        $position = 0;

        return collect(config('site.sections'))
            ->map(function (array $default, string $key) use ($rows, &$position) {
                $position++;

                return $rows->get($key) ?? new static([
                    'key' => $key,
                    'order' => $position,
                    'is_active' => true,
                ] + Arr::only($default, ['eyebrow', 'title', 'subtitle']));
            })
            ->sortBy('order');
    }

    // Enregistre en base les sections qui n'y sont pas encore, pour les rendre modifiables
    public static function syncDefaults(): void
    {
        static::resolved()
            ->reject(fn (self $section) => $section->exists)
            ->each(fn (self $section) => $section->save());
    }

    public function getLabelAttribute(): string
    {
        return config("site.sections.{$this->key}.label", $this->key);
    }

    public function getHintAttribute(): ?string
    {
        return config("site.sections.{$this->key}.hint");
    }
}
