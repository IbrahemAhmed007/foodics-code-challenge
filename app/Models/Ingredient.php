<?php

namespace App\Models;

use Database\Factories\IngredientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    /** @use HasFactory<IngredientFactory> */
    use HasFactory;

    public $fillable = ['name', 'available_quantity', 'alert_quantity'];
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
