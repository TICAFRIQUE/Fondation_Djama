<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Allège les images envoyées depuis l'admin : redimensionnement à une largeur
 * raisonnable pour le web et recompression, sans changer leur format ni leur chemin.
 */
class ImageOptimizer
{
    // Enregistre le fichier sur le disque public puis l'optimise si c'est une image
    public static function store(UploadedFile $file, string $directory, int $maxWidth = 1920): string
    {
        $path = $file->store($directory, 'public');

        static::optimize(Storage::disk('public')->path($path), $maxWidth);

        return $path;
    }

    public static function optimize(string $absolutePath, int $maxWidth = 1920, int $quality = 82): bool
    {
        if (! extension_loaded('gd') || ! is_file($absolutePath)) {
            return false;
        }

        try {
            $info = @getimagesize($absolutePath);
            if (! $info) {
                return false;
            }

            [$width, $height, $type] = $info;

            // Les très grandes images demanderaient trop de mémoire à GD
            if ($width * $height > 40_000_000) {
                return false;
            }

            $source = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($absolutePath),
                IMAGETYPE_PNG => @imagecreatefrompng($absolutePath),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolutePath) : false,
                default => false,
            };
            if (! $source) {
                return false;
            }

            if ($type === IMAGETYPE_JPEG) {
                $source = static::fixOrientation($source, $absolutePath);
                $width = imagesx($source);
                $height = imagesy($source);
            }

            $resized = $width > $maxWidth;
            if ($resized) {
                $newHeight = (int) round($height * $maxWidth / $width);
                $target = imagecreatetruecolor($maxWidth, $newHeight);
                imagealphablending($target, false);
                imagesavealpha($target, true);
                imagecopyresampled($target, $source, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
                imagedestroy($source);
                $source = $target;
            }

            $temp = $absolutePath . '.opt';
            $written = match ($type) {
                IMAGETYPE_JPEG => imagejpeg($source, $temp, $quality),
                IMAGETYPE_PNG => static::savePng($source, $temp),
                IMAGETYPE_WEBP => imagewebp($source, $temp, $quality),
            };
            imagedestroy($source);

            // On ne remplace l'original que si le résultat est réellement plus léger
            if ($written && ($resized || filesize($temp) < filesize($absolutePath))) {
                return rename($temp, $absolutePath);
            }

            @unlink($temp);

            return false;
        } catch (\Throwable $e) {
            // L'optimisation ne doit jamais faire échouer un envoi d'image
            report($e);

            return false;
        }
    }

    protected static function savePng(\GdImage $image, string $path): bool
    {
        imagealphablending($image, false);
        imagesavealpha($image, true);

        return imagepng($image, $path, 9);
    }

    // Les photos de téléphone sont souvent stockées couchées avec une rotation EXIF
    protected static function fixOrientation(\GdImage $image, string $path): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = @exif_read_data($path)['Orientation'] ?? 1;
        $angle = [3 => 180, 6 => -90, 8 => 90][$orientation] ?? 0;

        return $angle ? (imagerotate($image, $angle, 0) ?: $image) : $image;
    }
}
