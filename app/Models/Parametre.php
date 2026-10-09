<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parametre extends Model implements HasMedia
{
    //
    use HasFactory, InteractsWithMedia ;

    public $incrementing = false;

    protected $fillable = [
        //socials networks link
        'lien_facebook',
        'lien_instagram',
        'lien_twitter',
        'lien_linkedin',
        'lien_tiktok',
        'lien_youtube',

        //infos application
        'nom_projet', //nom du projet || nom de l'entreprise
        'slogan',
        'description_projet', //description du projet
        'contact_principal',
        'contact_secondaire',
        'contact_whatsapp',
        'email_principal',
        'email_secondaire',
        'localisation',
        'google_maps',
        'siege_social',

        //seo
        'meta_title',
        'meta_description',
        'meta_keywords',

        //security
        'mode_maintenance',
    ];




    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'parametres', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }
}
