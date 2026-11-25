{{-- Linha de "nenhum registro" dentro de uma <x-table>. --}}
@props([
    'colspan' => 1,
    'icon' => 'fas fa-inbox',
    'message' => 'Nenhum registro encontrado.',
])

<tr>
    <td colspan="{{ $colspan }}" class="text-center text-muted py-5">
        <i class="{{ $icon }} fa-2x d-block mb-2" style="opacity: .45;"></i>
        {{ $message }}
    </td>
</tr>
