{{-- Perfil de usuário (papel do Spatie), com o nome amigável de Perfis::ROLE_LABELS. --}}
@props(['role'])

@php
    $label = \App\Support\Perfis::ROLE_LABELS[$role] ?? ucfirst((string) $role);
    [$color, $icon] = match ($role) {
        'administrador' => ['primary', 'fas fa-user-shield'],
        'almoxarife' => ['info', 'fas fa-dolly'],
        default => ['muted', 'fas fa-user'],
    };
@endphp

<x-badge :color="$color" :icon="$icon">{{ $label }}</x-badge>
