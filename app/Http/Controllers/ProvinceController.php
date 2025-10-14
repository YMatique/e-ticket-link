<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
       /**
     * Display a listing of provinces.
     */
    public function index()
    {
        $provinces = Province::withCount('cities')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.provinces.index', compact('provinces'));
    }

    /**
     * Show the form for creating a new province.
     */
    public function create()
    {
        return view('provinces.create');
    }

    /**
     * Store a newly created province in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:provinces,name',
            'code' => 'required|string|max:10|unique:provinces,code',
        ], [
            'name.required' => 'O nome da província é obrigatório.',
            'name.unique' => 'Já existe uma província com este nome.',
            'code.required' => 'O código da província é obrigatório.',
            'code.unique' => 'Já existe uma província com este código.',
        ]);

        // try {
        //     Province::create($validated);

        //     return redirect()
        //         ->route('provinces.index')
        //         ->with('success', 'Província criada com sucesso!');
        // } catch (\Exception $e) {
        //     return back()
        //         ->withInput()
        //         ->with('error', 'Erro ao criar província: ' . $e->getMessage());
        // }
           try {
            Province::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Província criada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar província: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified province.
     */
    public function show(Province $province)
    {
        $province->load(['cities' => function($query) {
            $query->orderBy('name');
        }]);

        $stats = [
            'total_cities' => $province->cities->count(),
            'total_origin_routes' => $province->originRoutes()->count(),
            'total_destination_routes' => $province->destinationRoutes()->count(),
        ];

        return view('admin.provinces.show', compact('province', 'stats'));
    }

    /**
     * Show the form for editing the specified province.
     */
    public function edit(Province $province)
    {
        return view('provinces.edit', compact('province'));
    }

    /**
     * Update the specified province in storage.
     */
    public function update(Request $request, Province $province)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:provinces,name,' . $province->id,
            'code' => 'required|string|max:10|unique:provinces,code,' . $province->id,
        ], [
            'name.required' => 'O nome da província é obrigatório.',
            'name.unique' => 'Já existe uma província com este nome.',
            'code.required' => 'O código da província é obrigatório.',
            'code.unique' => 'Já existe uma província com este código.',
        ]);

        // try {
        //     $province->update($validated);

        //     return redirect()
        //         ->route('provinces.index')
        //         ->with('success', 'Província atualizada com sucesso!');
        // } catch (\Exception $e) {
        //     return back()
        //         ->withInput()
        //         ->with('error', 'Erro ao atualizar província: ' . $e->getMessage());
        // }
         try {
            $province->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Província atualizada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar província: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified province from storage.
     */
    public function destroy(Province $province)
    {
        try {
            // Verificar se há cidades associadas
            if ($province->cities()->count() > 0) {
                return back()->with('error', 'Não é possível excluir esta província pois existem cidades associadas.');
            }

            $province->delete();

            // return redirect()
            //     ->route('provinces.index')
            //     ->with('success', 'Província excluída com sucesso!');
            return response()->json([
                'success' => true,
                'message' => 'Província excluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            // return back()->with('error', 'Erro ao excluir província: ' . $e->getMessage());
             return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir província: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get cities by province (para uso em selects dinâmicos)
     */
    public function getCities(Province $province)
    {
        $cities = $province->cities()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }
}
