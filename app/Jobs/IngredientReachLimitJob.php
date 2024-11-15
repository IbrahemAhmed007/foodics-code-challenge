<?php

namespace App\Jobs;

use App\Models\Ingredient;
use App\Models\User;
use App\Notifications\IngredientReachLimitNotification;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Notification;

class IngredientReachLimitJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Ingredient $ingredient)
    {
        //
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->ingredient->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get Admins
        $admins = User::get();
        Notification::send($admins, new IngredientReachLimitNotification($this->ingredient));

    }
}
