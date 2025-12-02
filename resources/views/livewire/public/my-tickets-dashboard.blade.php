<div class="container py-4">
    <div class="row">
        <div class="col-lg-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Meus Bilhetes</h4>
                    <p class="text-muted mb-0">Gerencie todos os seus bilhetes aqui</p>
                </div>
                <a href="{{ route('trips') }}" class="btn btn-primary" wire:navigate>
                    <i class="ph-magnifying-glass me-2"></i>
                    Buscar Viagens
                </a>
            </div>

            <!-- Estatísticas -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="ph-ticket ph-3x text-primary mb-2"></i>
                            <h2 class="mb-0">{{ $stats['total'] }}</h2>
                            <p class="text-muted mb-0">Total de Bilhetes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="ph-check-circle ph-3x text-success mb-2"></i>
                            <h2 class="mb-0">{{ $stats['active'] }}</h2>
                            <p class="text-muted mb-0">Bilhetes Ativos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="ph-users ph-3x text-info mb-2"></i>
                            <h2 class="mb-0">{{ $stats['passengers'] }}</h2>
                            <p class="text-muted mb-0">Passageiros</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros e Pesquisa -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Pesquisar</label>
                            <div class="form-control-feedback form-control-feedback-start">
                                <input type="text" 
                                       class="form-control" 
                                       wire:model.live.debounce.300ms="search"
                                       placeholder="Número do bilhete ou nome do passageiro">
                                <div class="form-control-feedback-icon">
                                    <i class="ph-magnifying-glass text-muted"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model.live="statusFilter">
                                <option value="">Todos os status</option>
                                <option value="reserved">Reservados</option>
                                <option value="paid">Pagos</option>
                                <option value="validated">Validados</option>
                                <option value="cancelled">Cancelados</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" 
                                    class="btn btn-light w-100" 
                                    wire:click="clearFilters">
                                <i class="ph-x me-2"></i>
                                Limpar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Bilhetes -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-list me-2"></i>
                        Lista de Bilhetes
                    </h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Passageiro</th>
                                <th>Rota</th>
                                <th>Data</th>
                                <th>Assento</th>
                                <th>Preço</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr wire:key="ticket-{{ $ticket->id }}">
                                <td>
                                    <span class="fw-bold">{{ $ticket->ticket_number }}</span>
                                    @if($ticket->isPurchasedForOther())
                                        <br>
                                        <small class="text-muted">
                                            <i class="ph-gift"></i> Comprado para outro
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $ticket->passenger->first_name }} {{ $ticket->passenger->last_name }}</strong>
                                    </div>
                                    <small class="text-muted">{{ $ticket->passenger->email }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <strong>{{ $ticket->schedule->route->originCity->name }}</strong>
                                        <i class="ph-arrow-right mx-2 text-muted"></i>
                                        <strong>{{ $ticket->schedule->route->destinationCity->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $ticket->schedule->departure_date->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $ticket->schedule->departure_time }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary fs-6">{{ $ticket->seat_number }}</span>
                                </td>
                                <td>
                                    <strong>{{ number_format($ticket->price, 2) }} MT</strong>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'reserved' => 'warning',
                                            'paid' => 'success',
                                            'validated' => 'info',
                                            'cancelled' => 'danger',
                                        ];
                                        $statusLabels = [
                                            'reserved' => 'Reservado',
                                            'paid' => 'Pago',
                                            'validated' => 'Validado',
                                            'cancelled' => 'Cancelado',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'secondary' }}">
                                        {{ $statusLabels[$ticket->status] ?? ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('ticket.show', $ticket) }}" 
                                           class="btn btn-light" 
                                           title="Ver detalhes"
                                           wire:navigate>
                                            <i class="ph-eye"></i>
                                        </a>
                                        <a href="{{ route('ticket.pdf', $ticket) }}" 
                                           class="btn btn-light" 
                                           title="Baixar PDF" 
                                           target="_blank">
                                            <i class="ph-file-pdf"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="ph-ticket ph-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-3">
                                        @if($search || $statusFilter)
                                            Nenhum bilhete encontrado com os filtros aplicados.
                                        @else
                                            Você ainda não comprou nenhum bilhete.
                                        @endif
                                    </p>
                                    @if(!$search && !$statusFilter)
                                        <a href="{{ route('trips') }}" class="btn btn-primary" wire:navigate>
                                            <i class="ph-magnifying-glass me-2"></i>
                                            Buscar Viagens
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-light" wire:click="clearFilters">
                                            <i class="ph-x me-2"></i>
                                            Limpar Filtros
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                <div class="card-footer">
                    {{ $tickets->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>