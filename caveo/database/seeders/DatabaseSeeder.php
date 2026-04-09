<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Exécute les seeders de l'application.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);
    }
}
