<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // No WithoutModelEvents — the HasPublicId boot hook must fire to assign ULIDs.

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::query()->where('email', 'test@example.com')->doesntExist()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        if (app()->environment('local')) {
            $this->call(DevSeeder::class);
        }
    }
}
