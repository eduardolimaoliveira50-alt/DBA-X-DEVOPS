<?php

namespace Database\Seeders;

use App\Models\Produto;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Perfis, contas de demonstração e, se o banco estiver vazio, a massa que imita a produção.
     * Idempotente: roda a cada publicação sem duplicar nada nem trocar senhas existentes.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $senha = (string) env('ALMOX_SENHA_DEMO', 'almox2026');
        foreach ([
            ['admin@almoxarifado.test', 'Ana Administradora', 'administrador'],
            ['almoxarife@almoxarifado.test', 'Bruno Almoxarife', 'almoxarife'],
            ['consulta@almoxarifado.test', 'Carla Consulta', 'consulta'],
        ] as [$email, $nome, $papel]) {
            User::query()->firstOrCreate(['email' => $email], ['name' => $nome, 'password' => $senha])->syncRoles([$papel]);
        }

        // no Render a carga roda depois, em segundo plano (scripts/render/start.sh), para a porta abrir logo
        if (! env('ALMOX_SEM_MASSA') && ! Produto::query()->exists()) {
            $this->call(MassaProducaoSeeder::class);
        }
    }
}
