<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

/** Auditoria: quem cadastrou ou alterou produtos e fornecedores, quando e o que mudou. */
class AuditController extends Controller
{
    public function __invoke(Request $request): View
    {
        $evento = $request->input('filter.event');
        $autor = $request->input('filter.causer_id');

        $activities = Activity::query()
            ->with(['causer', 'subject'])
            ->when(in_array($evento, ['created', 'updated', 'deleted'], true), fn ($q) => $q->where('event', $evento))
            ->when(is_numeric($autor), fn ($q) => $q->where('causer_id', (int) $autor))
            ->latest('id')
            ->paginate(50)
            ->withQueryString();

        return view('admin.auditoria.index', [
            'activities' => $activities,
            'users' => User::withTrashed()->whereIn('id', Activity::query()->whereNotNull('causer_id')->select('causer_id'))
                ->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
