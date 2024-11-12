<?php

namespace App\Observers;

use App\Jobs\IngredientReachLimitJob;
use App\Models\Ingredient;

class IngredientObserver
{
    /**
     * Handle the Ingredient "created" event.
     */
    public function created(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "updated" event.
     */
    public function updated(Ingredient $ingredient): void
    {
        if ($ingredient->wasChanged('available_quantity')
            && $ingredient->getOriginal('available_quantity') > $ingredient->alert_quantity
            && $ingredient->available_quantity <= $ingredient->alert_quantity) {
            IngredientReachLimitJob::dispatch($ingredient);
        }


    }

    /**
     * Handle the Ingredient "deleted" event.
     */
    public function deleted(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "restored" event.
     */
    public function restored(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "force deleted" event.
     */
    public function forceDeleted(Ingredient $ingredient): void
    {
        //
    }
}
