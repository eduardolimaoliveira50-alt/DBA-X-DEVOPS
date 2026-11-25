<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BackfillEmailCorporativoTest extends TestCase
{
    use RefreshDatabase;

    public function test_preenche_em_lotes_sem_sobrescrever(): void
    {
        // 25 registros gravados pela versão antiga (só email) e 1 já preenchido com outro valor.
        DB::table('clientes')->insert(collect(range(1, 25))->map(fn ($n) => ['email' => "c{$n}@exemplo.com"])->all());
        DB::table('clientes')->insert(['email' => 'x@exemplo.com', 'email_corporativo' => 'ja-preenchido@exemplo.com']);

        $this->artisan('clientes:backfill-email-corporativo', ['--lote' => 10])
            ->expectsOutputToContain('25 registro(s) em 3 lote(s)')
            ->assertSuccessful();

        $this->assertSame(0, DB::table('clientes')->whereNull('email_corporativo')->count());
        $this->assertSame('ja-preenchido@exemplo.com', DB::table('clientes')->where('email', 'x@exemplo.com')->value('email_corporativo'));
    }

    public function test_pode_ser_repetido(): void
    {
        DB::table('clientes')->insert(['email' => 'a@exemplo.com']);

        $this->artisan('clientes:backfill-email-corporativo')->assertSuccessful();
        $this->artisan('clientes:backfill-email-corporativo')
            ->expectsOutputToContain('0 registro(s)')
            ->assertSuccessful();
    }

    public function test_verificacao_de_integridade(): void
    {
        DB::table('clientes')->insert(['email' => 'a@exemplo.com']);
        $this->artisan('clientes:verificar-integridade')->assertFailed();

        $this->artisan('clientes:backfill-email-corporativo')->assertSuccessful();
        $this->artisan('clientes:verificar-integridade')->assertSuccessful();
    }
}
