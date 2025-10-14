<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
      /**
     * Display a listing of buses.
     */
    public function index(Request $request)
    {
        $query = Bus::query();

        // Filtro por modelo
        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        // Filtro por matrícula
        if ($request->filled('registration_number')) {
            $query->where('registration_number', 'like', '%' . $request->registration_number . '%');
        }

        // Filtro por status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Busca geral
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('model', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $buses = $query->paginate(20);

        // Estatísticas
        $stats = [
            'total_buses' => Bus::count(),
            'active_buses' => Bus::where('is_active', true)->count(),
            'inactive_buses' => Bus::where('is_active', false)->count(),
            'total_seats' => Bus::where('is_active', true)->sum('total_seats'),
        ];

        return view('admin.buses.index', compact('buses', 'stats'));
    }

    /**
     * Store a newly created bus in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:20|unique:buses,registration_number',
            'model' => 'required|string|max:100',
            'total_seats' => 'required|integer|min:1|max:100',
        ], [
            'registration_number.required' => 'O número de matrícula é obrigatório.',
            'registration_number.unique' => 'Já existe um autocarro com esta matrícula.',
            'model.required' => 'O modelo do autocarro é obrigatório.',
            'total_seats.required' => 'O número de lugares é obrigatório.',
            'total_seats.min' => 'O autocarro deve ter pelo menos 1 lugar.',
            'total_seats.max' => 'O número máximo de lugares é 100.',
        ]);

        try {
            // Converter matrícula para maiúsculas
            $validated['registration_number'] = strtoupper($validated['registration_number']);
            
            // Converter checkbox para boolean
            $validated['is_active'] = $request->has('is_active') ? true : false;

            // Criar configuração padrão de assentos (se não fornecida)
            if (!$request->filled('seat_configuration')) {
                $validated['seat_configuration'] = $this->generateDefaultSeatConfiguration($validated['total_seats']);
            }

            Bus::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Autocarro criado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar autocarro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified bus.
     */
    public function show(Bus $bus)
    {
        $bus->load('schedules');

        $stats = [
            'total_schedules' => $bus->schedules()->count(),
            'active_schedules' => $bus->schedules()->where('status', 'active')->count(),
            'completed_schedules' => $bus->schedules()->where('status', 'completed')->count(),
            'total_trips' => $bus->schedules()->whereIn('status', ['departed', 'completed'])->count(),
        ];

        return view('admin.buses.show', compact('bus', 'stats'));
    }

    /**
     * Update the specified bus in storage.
     */
    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:20|unique:buses,registration_number,' . $bus->id,
            'model' => 'required|string|max:100',
            'total_seats' => 'required|integer|min:1|max:100',
        ], [
            'registration_number.required' => 'O número de matrícula é obrigatório.',
            'registration_number.unique' => 'Já existe um autocarro com esta matrícula.',
            'model.required' => 'O modelo do autocarro é obrigatório.',
            'total_seats.required' => 'O número de lugares é obrigatório.',
            'total_seats.min' => 'O autocarro deve ter pelo menos 1 lugar.',
            'total_seats.max' => 'O número máximo de lugares é 100.',
        ]);

        try {
            // Converter matrícula para maiúsculas
            $validated['registration_number'] = strtoupper($validated['registration_number']);
            
            // Converter checkbox para boolean
            $validated['is_active'] = $request->has('is_active') ? true : false;

            // Se o número de assentos mudou, recalcular configuração
            if ($bus->total_seats != $validated['total_seats']) {
                $validated['seat_configuration'] = $this->generateDefaultSeatConfiguration($validated['total_seats']);
            }

            $bus->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Autocarro atualizado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar autocarro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified bus from storage.
     */
    public function destroy(Bus $bus)
    {
        try {
            // Verificar se há horários associados
            if ($bus->schedules()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível excluir este autocarro pois existem horários/viagens associadas.'
                ], 422);
            }

            $bus->delete();

            return response()->json([
                'success' => true,
                'message' => 'Autocarro excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir autocarro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle bus active status.
     */
    public function toggleStatus(Bus $bus)
    {
        try {
            $bus->update([
                'is_active' => !$bus->is_active
            ]);

            $status = $bus->is_active ? 'ativado' : 'desativado';

            return response()->json([
                'success' => true,
                'message' => "Autocarro {$status} com sucesso!",
                'is_active' => $bus->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status do autocarro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show seat configuration page.
     */
    public function seatConfiguration(Bus $bus)
    {
        return view('admin.buses.seat-configuration', compact('bus'));
    }

    /**
     * Update seat configuration.
     */
    public function updateSeatConfiguration(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'seat_configuration' => 'required|array',
        ]);

        try {
            $bus->update([
                'seat_configuration' => $validated['seat_configuration']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Configuração de assentos atualizada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar configuração: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate default seat configuration.
     */
    private function generateDefaultSeatConfiguration(int $totalSeats): array
    {
        $configuration = [];
        $seatsPerRow = 4; // 2 assentos de cada lado
        $rows = ceil($totalSeats / $seatsPerRow);

        $seatNumber = 1;
        for ($row = 1; $row <= $rows; $row++) {
            $rowSeats = [];
            
            for ($col = 1; $col <= $seatsPerRow && $seatNumber <= $totalSeats; $col++) {
                $rowSeats[] = [
                    'number' => $seatNumber,
                    'position' => $col <= 2 ? 'left' : 'right', // 2 esquerda, 2 direita
                    'type' => 'standard',
                    'available' => true
                ];
                $seatNumber++;
            }
            
            $configuration["row_{$row}"] = $rowSeats;
        }

        return $configuration;
    }
}
