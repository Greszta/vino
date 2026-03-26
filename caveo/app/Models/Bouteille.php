<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bouteille extends Model
{
    use HasFactory;

    protected $table = 'bouteilles';

    protected $fillable = [
        'code_saq',
        'nom',
        'type',
        'pays',
        'cepage',
        'millesime',
        'taux_alcool',
        'prix',
        'format',
        'image',
        'pastille_gout',
        'est_saq',
    ];
}
