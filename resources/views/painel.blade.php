<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laboratório DBA x DevOps</title>
    <style>
        :root { --bg:#f6f7f9; --card:#fff; --tx:#1d2330; --mut:#5f6b7a; --bd:#dfe3e8; --ac:#1f6feb; --ok:#1a7f37; --er:#cf222e; }
        @media (prefers-color-scheme: dark) { :root { --bg:#0f1218; --card:#171b23; --tx:#e6e9ef; --mut:#9aa4b2; --bd:#2a303b; --ac:#58a6ff; --ok:#3fb950; --er:#f85149; } }
        * { box-sizing: border-box; }
        body { margin:0; font:15px/1.5 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; background:var(--bg); color:var(--tx); }
        main { max-width:960px; margin:0 auto; padding:24px 16px 48px; }
        h1 { font-size:22px; margin:0 0 4px; } h2 { font-size:15px; margin:0 0 12px; color:var(--mut); text-transform:uppercase; letter-spacing:.04em; }
        .sub { color:var(--mut); margin:0 0 24px; }
        .grid { display:grid; gap:16px; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); }
        .card { background:var(--card); border:1px solid var(--bd); border-radius:10px; padding:16px; overflow-x:auto; }
        .fase { border-left:4px solid var(--ac); } .fase strong { font-size:20px; display:block; }
        table { border-collapse:collapse; width:100%; font-size:14px; } td,th { text-align:left; padding:6px 8px; border-bottom:1px solid var(--bd); }
        th { color:var(--mut); font-weight:600; } code { font-size:13px; }
        .num { font-variant-numeric:tabular-nums; text-align:right; } .zero { color:var(--ok); } .alerta { color:var(--er); font-weight:600; }
        footer { margin-top:24px; color:var(--mut); font-size:13px; }
    </style>
</head>
<body>
<main>
    <h1>Laboratório DBA x DevOps</h1>
    <p class="sub">Evolução de esquema pelo padrão Expand-Contract (Laravel 12 + PostgreSQL). Dados fictícios.</p>

    <div class="card fase" style="margin-bottom:16px">
        <h2>Fase atual do esquema</h2>
        <strong>{{ $fase[0] }}</strong>
        <span>{{ $fase[1] }}</span>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Tabela clientes</h2>
            <table>
                <tr><th>Coluna</th><th>Tipo</th><th>Aceita nulo</th></tr>
                @foreach ($colunas as $c)
                    <tr><td><code>{{ $c['name'] }}</code></td><td>{{ $c['type'] }}</td><td>{{ $c['nullable'] ? 'sim' : 'não' }}</td></tr>
                @endforeach
            </table>
        </div>
        <div class="card">
            <h2>Integridade</h2>
            <table>
                @foreach ($indicadores as $nome => $valor)
                    @php $verificacao = $nome !== 'Clientes'; @endphp
                    <tr><td>{{ $nome }}</td><td class="num {{ $verificacao ? ($valor === 0 ? 'zero' : 'alerta') : '' }}">{{ number_format($valor, 0, ',', '.') }}</td></tr>
                @endforeach
            </table>
        </div>
        <div class="card">
            <h2>Como a aplicação lê (amostra)</h2>
            <table>
                @forelse ($amostra as $email)
                    <tr><td><code>{{ $email }}</code></td></tr>
                @empty
                    <tr><td>Nenhum cliente.</td></tr>
                @endforelse
            </table>
        </div>
        <div class="card">
            <h2>Migrations aplicadas</h2>
            <table>
                <tr><th>Migration</th><th class="num">Lote</th></tr>
                @foreach ($migrations as $m)
                    <tr><td><code>{{ $m->migration }}</code></td><td class="num">{{ $m->batch }}</td></tr>
                @endforeach
            </table>
        </div>
    </div>

    <footer>Versão publicada: <code>{{ $versao }}</code> · {{ \Illuminate\Support\Str::before($banco, ' on ') }}</footer>
</main>
</body>
</html>
