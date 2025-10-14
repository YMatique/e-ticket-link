<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
      /**
     * Display a listing of routes.
     */
    public function index(Request $request)
    {
        $query = Route::with(['originCity.province', 'destinationCity.province']);

        // Filtro por cidade origem
        if ($request->filled('origin_city_id')) {
            $query->where('origin_city_id', $request->origin_city_id);
        }

        // Filtro por cidade destino
        if ($request->filled('destination_city_id')) {
            $query->where('destination_city_id', $request->destination_city_id);
        }

        // Filtro por província origem
        if ($request->filled('origin_province_id')) {
            $query->whereHas('originCity', function($q) use ($request) {
                $q->where('province_id', $request->origin_province_id);
            });
        }

        // Filtro por província destino
        if ($request->filled('destination_province_id')) {
            $query->whereHas('destinationCity', function($q) use ($request) {
                $q->where('province_id', $request->destination_province_id);
            });
        }

        // Filtro por status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Busca por nome (origem ou destino)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('originCity', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })->orWhereHas('destinationCity', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $routes = $query->paginate(20);

        // Dados para filtros
        $provinces = Province::orderBy('name')->get();
        $cities = City::orderBy('name')->get();

        // Estatísticas
        $stats = [
            'total_routes' => Route::count(),
            'active_routes' => Route::where('is_active', true)->count(),
            'inactive_routes' => Route::where('is_active', false)->count(),
            'total_distance' => Route::sum('distance_km'),
        ];

        return view('admin.routes.index', compact('routes', 'provinces', 'cities', 'stats'));
    }

    /**
     * Store a newly created route in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin_city_id' => 'required|exists:cities,id',
            'destination_city_id' => 'required|exists:cities,id|different:origin_city_id',
            'distance_km' => 'nullable|numeric|min:0|max:9999.99',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
            // 'is_active' => 'boolean',
        ], [
            'origin_city_id.required' => 'A cidade de origem é obrigatória.',
            'origin_city_id.exists' => 'Cidade de origem inválida.',
            'destination_city_id.required' => 'A cidade de destino é obrigatória.',
            'destination_city_id.exists' => 'Cidade de destino inválida.',
            'destination_city_id.different' => 'A cidade de destino deve ser diferente da origem.',
            'distance_km.numeric' => 'A distância deve ser um número válido.',
            'distance_km.min' => 'A distância não pode ser negativa.',
            'estimated_duration_minutes.integer' => 'A duração deve ser um número inteiro.',
            'estimated_duration_minutes.min' => 'A duração não pode ser negativa.',
        ]);

        try {
            $validated['is_active'] = $request->boolean('is_active', true);
            // Verificar se já existe uma rota com a mesma origem e destino
            $exists = Route::where('origin_city_id', $validated['origin_city_id'])
                ->where('destination_city_id', $validated['destination_city_id'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Já existe uma rota cadastrada entre estas cidades.'
                ], 422);
            }

            // Definir status padrão se não informado
            $validated['is_active'] = $request->boolean('is_active', true);

            Route::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Rota criada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar rota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified route.
     */
    public function show(Route $route)
    {
        $route->load(['originCity.province', 'destinationCity.province', 'schedules']);

        $stats = [
            'total_schedules' => $route->schedules()->count(),
            'active_schedules' => $route->schedules()->where('status', 'scheduled')->count(),
            'completed_schedules' => $route->schedules()->where('status', 'completed')->count(),
            'total_tickets' => $route->schedules()->withCount('tickets')->get()->sum('tickets_count'),
        ];

        return view('admin.routes.show', compact('route', 'stats'));
    }

    /**
     * Update the specified route in storage.
     */
    public function update(Request $request, Route $route)
    {
        $validated = $request->validate([
            'origin_city_id' => 'required|exists:cities,id',
            'destination_city_id' => 'required|exists:cities,id|different:origin_city_id',
            'distance_km' => 'nullable|numeric|min:0|max:9999.99',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
            // 'is_active' => 'boolean',
        ], [
            'origin_city_id.required' => 'A cidade de origem é obrigatória.',
            'origin_city_id.exists' => 'Cidade de origem inválida.',
            'destination_city_id.required' => 'A cidade de destino é obrigatória.',
            'destination_city_id.exists' => 'Cidade de destino inválida.',
            'destination_city_id.different' => 'A cidade de destino deve ser diferente da origem.',
            'distance_km.numeric' => 'A distância deve ser um número válido.',
            'distance_km.min' => 'A distância não pode ser negativa.',
            'estimated_duration_minutes.integer' => 'A duração deve ser um número inteiro.',
            'estimated_duration_minutes.min' => 'A duração não pode ser negativa.',
        ]);

        try {
            // Verificar se já existe outra rota com a mesma origem e destino
            $exists = Route::where('origin_city_id', $validated['origin_city_id'])
                ->where('destination_city_id', $validated['destination_city_id'])
                ->where('id', '!=', $route->id)
                ->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Já existe uma rota cadastrada entre estas cidades.'
                ], 422);
            }

            // $validated['is_active'] = $request->boolean('is_active', $route->is_active);
              // Converter checkbox para boolean
            $validated['is_active'] = $request->has('is_active') ? true : false;

            $route->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Rota atualizada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar rota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified route from storage.
     */
    public function destroy(Route $route)
    {
        try {
            // Verificar se há horários associados
            if ($route->schedules()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível excluir esta rota pois existem horários/viagens associadas.'
                ], 422);
            }

            $route->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rota excluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir rota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle route active status.
     */
    public function toggleStatus(Route $route)
    {
        try {
            $route->update([
                'is_active' => !$route->is_active
            ]);

            $status = $route->is_active ? 'ativada' : 'desativada';

            return response()->json([
                'success' => true,
                'message' => "Rota {$status} com sucesso!",
                'is_active' => $route->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status da rota: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cities by province (API endpoint for dynamic selects).
     */
    public function getCitiesByProvince(Request $request)
    {
        $provinceId = $request->get('province_id');
        
        $cities = City::where('province_id', $provinceId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }
}
