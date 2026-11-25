<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Somente dados fictícios (o laboratório nunca usa dados reais).
     */
    public function run(): void
    {
        $this->call(ClientesFicticiosSeeder::class);
    }
}
