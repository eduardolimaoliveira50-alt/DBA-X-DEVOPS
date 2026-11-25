<?php

namespace App\Support;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Perfis e permissões do almoxarifado (Spatie). Mudar aqui e rodar o seeder sincroniza o banco.
 */
final class Perfis
{
    public const PRODUTOS = 'produtos.gerenciar';

    public const FORNECEDORES = 'fornecedores.gerenciar';

    public const MOVIMENTACOES = 'movimentacoes.registrar';

    public const AUDITORIA = 'auditoria.visualizar';

    public const PERMISSOES = [self::PRODUTOS, self::FORNECEDORES, self::MOVIMENTACOES, self::AUDITORIA];

    public const PAPEIS = [
        'administrador' => self::PERMISSOES,
        'almoxarife' => [self::PRODUTOS, self::FORNECEDORES, self::MOVIMENTACOES],
        'consulta' => [],
    ];

    public const ROLE_LABELS = [
        'administrador' => 'Administrador',
        'almoxarife' => 'Almoxarife',
        'consulta' => 'Consulta',
    ];

    public static function sync(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSOES as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        foreach (self::PAPEIS as $papel => $permissoes) {
            Role::findOrCreate($papel, 'web')->syncPermissions($permissoes);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
