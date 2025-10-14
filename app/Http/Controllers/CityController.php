<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class CityController extends Controller
{
      /**
     * Display a listing of cities.
     */
    public function index(Request $request)
    {
        $query = City::with('province');

        // Filtro por província
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // Busca por nome
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $cities = $query->orderBy('name')->paginate(20);
        $provinces = Province::orderBy('name')->get();

        return view('admin.cities.index', compact('cities', 'provinces'));
    }

    /**
     * Show the form for creating a new city.
     */
    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        return view('cities.create', compact('provinces'));
    }

    /**
     * Store a newly created city in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'province_id' => 'required|exists:provinces,id',
        ], [
            'name.required' => 'O nome da cidade é obrigatório.',
            'province_id.required' => 'A província é obrigatória.',
            'province_id.exists' => 'Província selecionada inválida.',
        ]);

        try {
            // Verificar se já existe uma cidade com o mesmo nome na mesma província
            $exists = City::where('name', $validated['name'])
                ->where('province_id', $validated['province_id'])
                ->exists();

            if ($exists) {
                // return back()
                //     ->withInput()
                //     ->with('error', 'Já existe uma cidade com este nome nesta província.');
                return response()->json([
                    'success' => false,
                    'message' => 'Já existe uma cidade com este nome nesta província.'
                ], 422);
            }

            City::create($validated);

            // return redirect()
            //     ->route('cities.index')
            //     ->with('success', 'Cidade criada com sucesso!');
             return response()->json([
                'success' => true,
                'message' => 'Cidade criada com sucesso!'
            ]);
        } catch (\Exception $e) {
            // return back()
            //     ->withInput()
            //     ->with('error', 'Erro ao criar cidade: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar cidade: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified city.
     */
    public function show(City $city)
    {
        $city->load('province');

        $stats = [
            'total_origin_routes' => $city->originRoutes()->count(),
            'total_destination_routes' => $city->destinationRoutes()->count(),
            'total_routes' => $city->originRoutes()->count() + $city->destinationRoutes()->count(),
        ];

        return view('admin.cities.show', compact('city', 'stats'));
    }

    /**
     * Show the form for editing the specified city.
     */
    public function edit(City $city)
    {
        $provinces = Province::orderBy('name')->get();
        return view('cities.edit', compact('city', 'provinces'));
    }

    /**
     * Update the specified city in storage.
     */
    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'province_id' => 'required|exists:provinces,id',
        ], [
            'name.required' => 'O nome da cidade é obrigatório.',
            'province_id.required' => 'A província é obrigatória.',
            'province_id.exists' => 'Província selecionada inválida.',
        ]);

        try {
            // Verificar se já existe outra cidade com o mesmo nome na mesma província
            $exists = City::where('name', $validated['name'])
                ->where('province_id', $validated['province_id'])
                ->where('id', '!=', $city->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withInput()
                    ->with('error', 'Já existe uma cidade com este nome nesta província.');
            }

            $city->update($validated);

            // return redirect()
            //     ->route('cities.index')
            //     ->with('success', 'Cidade atualizada com sucesso!');
         return response()->json([
                'success' => true,
                'message' => 'Cidade atualizada com sucesso!'
            ]);
        } catch (\Exception $e) {
            // return back()
            //     ->withInput()
            //     ->with('error', 'Erro ao atualizar cidade: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar cidade: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified city from storage.
     */
    public function destroy(City $city)
    {
        try {
            // Verificar se há rotas associadas
            $hasOriginRoutes = $city->originRoutes()->count() > 0;
            $hasDestinationRoutes = $city->destinationRoutes()->count() > 0;

            if ($hasOriginRoutes || $hasDestinationRoutes) {
                return back()->with('error', 'Não é possível excluir esta cidade pois existem rotas associadas.');
            }

            $city->delete();

            // return redirect()
            //     ->route('cities.index')
            //     ->with('success', 'Cidade excluída com sucesso!');
             return response()->json([
                'success' => true,
                'message' => 'Cidade excluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            // return back()->with('error', 'Erro ao excluir cidade: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir cidade: ' . $e->getMessage()
            ], 500);
        }
    }
}
