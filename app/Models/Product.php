<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'name',
        'categorie_id',
        'date',
        'file_name',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $appends = ['image_url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function getImageUrlAttribute()
    {
        // Si pas d'image, retourner l'image par défaut
        if (!$this->file_name) {
            return $this->defaultImageUrl();
        }

        // Si c'est déjà une URL complète (cas des anciennes données)
        if (filter_var($this->file_name, FILTER_VALIDATE_URL)) {
            return $this->file_name;
        }

        // Nettoyer le chemin du fichier
        $cleanPath = ltrim($this->file_name, '/');

        // Vérifier si le fichier existe
        if (Storage::disk('public')->exists($cleanPath)) {
            return asset("storage/{$cleanPath}");
        }

        // Retourner l'image par défaut si le fichier n'existe pas
        return $this->defaultImageUrl();
    }

    protected function defaultImageUrl()
    {
        return asset('storage/products/no_image.jpg');
    }
}
