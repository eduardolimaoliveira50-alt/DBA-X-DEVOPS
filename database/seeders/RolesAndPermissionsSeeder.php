<?php

namespace Database\Seeders;

use App\Support\Perfis;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Perfis::sync();
    }
}
