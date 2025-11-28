<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PassengerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $stats = [
        'total'         => Passenger::count(),
        'active'        => Passenger::where('is_active', true)->count(),
        'with_tickets'  => Passenger::has('tickets')->count(),
        'today'         => Passenger::whereDate('created_at', today())->count(),
    ];

        $query = Passenger::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $passengers = $query->withCount('tickets')
                            ->orderByDesc('created_at')
                            ->paginate(20)
                            ->withQueryString();

        return view('admin.passengers.index', compact('passengers','stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.passengers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:passengers,email',
            'phone'             => 'required|string|regex:/^\+258\s?(84|85|87)\d{7}$/',
            'document_type'     => 'required|in:BI,Passaporte,NUIT',
            'document_number'   => 'required|string|unique:passengers,document_number',
            'password'          => 'required|string|min:6|confirmed',
            'is_active'         => 'sometimes|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $passenger = Passenger::create($validated);

        return redirect()
            ->route('passengers.show', $passenger)
            ->with('success', 'Passageiro criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Passenger $passenger)
    {
        // $passenger->load(['tickets.schedule.route.originCity', 'tickets.schedule.route.destinationCity', 'tickets.schedule.bus']);

        // $tickets = $passenger->tickets()
        //     ->with('schedule.route', 'schedule.bus')
        //     ->latest()
        //     ->paginate(10);
        $passenger->loadCount('tickets');

    $tickets = $passenger->tickets()
        ->with(['schedule.route.originCity', 'schedule.route.destinationCity', 'schedule.bus'])
        ->latest()
        ->paginate(15);

        return view('admin.passengers.show', compact('passenger', 'tickets'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Passenger $passenger)
    {
        return view('admin.passengers.edit', compact('passenger'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Passenger $passenger)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => ['required', 'email', Rule::unique('passengers')->ignore($passenger->id)],
            'phone'             => 'required|string|regex:/^\+258\s?(84|85|87)\d{7}$/',
            'document_type'     => 'required|in:BI,Passaporte,NUIT',
            'document_number'   => ['required', 'string', Rule::unique('passengers')->ignore($passenger->id)],
            'password'          => 'nullable|string|min:6|confirmed',
            'is_active'         => 'sometimes|boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', $passenger->is_active);

        $passenger->update($validated);

        return redirect()
            ->route('admin.passengers.show', $passenger)
            ->with('success', 'Passageiro atualizado com sucesso!');
    }

/**
     * Desativa/reativa passageiro (soft block)
     */
    public function toggleStatus(Passenger $passenger)
    {
        $passenger->update(['is_active' => !$passenger->is_active]);

        $status = $passenger->is_active ? 'ativado' : 'desativado';

        return back()->with('success', "Passageiro {$status} com sucesso.");
    }

    /**
     * Apaga passageiro (só se não tiver bilhetes)
     */
    public function destroy(Passenger $passenger)
    {
        if ($passenger->tickets()->exists()) {
            return back()->with('error', 'Não pode eliminar um passageiro com bilhetes associados.');
        }

        $passenger->delete();

        return redirect()
            ->route('admin.passengers.index')
            ->with('success', 'Passageiro eliminado com sucesso.');
    }
}
