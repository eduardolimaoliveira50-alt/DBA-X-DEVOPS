import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    // endereços relativos nos arquivos gerados (fontes no CSS, partes do JS): o sistema roda em subpasta
    // (/controle-acesso) e "/build/..." apontaria para a raiz do servidor
    base: './',
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/login.js',
            ],
            refresh: true,
        }),
    ],
});
