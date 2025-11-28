@extends('layouts.app')

@section('content')
<div class="content">

    <!-- Page Header -->
    <div class="page-header page-header-light shadow mb-4">
        <div class="page-header-content d-flex">
            <div class="page-title">
                <h4>
                    <i class="ph-user me-2"></i>
                    <span class="fw-semibold">{{ $passenger->first_name }} {{ $passenger->last_name }}</span>
                </h4>
                <div class="text-muted">
                    <small>
                        <i class="ph-envelope me-1"></i> {{ $passenger->email }} •
                        <i class="ph-phone me-1"></i> {{ $passenger->phone }}
                    </small>
                </div>
            </div>
            <div class="my-auto ms-auto">
                <a href="{{ route('passengers.edit', $passenger) }}" class="btn btn-warning">
                    <i class="ph-pencil-simple me-2"></i> Editar
                </a>
                <button type="button" class="btn btn-{{ $passenger->is_active ? 'danger' : 'success' }} ms-2"
                        onclick="event.preventDefault(); document.getElementById('toggle-form').submit();">
                    <i class="ph-user-switch me-2"></i>
                    {{ $passenger->is_active ? 'Desativar' : 'Ativar' }}
                </button>
                <form id="toggle-form" action="{{ route('passengers.toggle', $passenger) }}" method="POST" class="d-inline">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary bg-opacity-10 border-primary">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-primary">{{ $passenger->tickets_count }}</h4>
                        <span>Bilhetes Emitidos</span>
                    </div>
                    <i class="ph-ticket ph-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-success bg-opacity-10 border-success">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-success">
                            {{ number_format($passenger->tickets()->where('status', 'paid')->sum('price'), 2) }} MT
                        </h4>
                        <span>Total Pago</span>
                    </div>
                    <i class="ph-money ph-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-info bg-opacity-10 border-info">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-info">{{ $passenger->tickets()->where('status', 'validated')->count() }}</h4>
                        <span>Viagens Realizadas</span>
                    </div>
                    <i class="ph-check-circle ph-2x text-info opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body {{ $passenger->is_active ? 'bg-success' : 'bg-danger' }} bg-opacity-10 border-{{ $passenger->is_active ? 'success' : 'danger' }}">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0 text-{{ $passenger->is_active ? 'success' : 'danger' }}">
                            {{ $passenger->is_active ? 'Ativo' : 'Inativo' }}
                        </h4>
                        <span>Estado da Conta</span>
                    </div>
                    <i class="ph-{{ $passenger->is_active ? 'user-check' : 'user-x' }} ph-2x text-{{ $passenger->is_active ? 'success' : 'danger' }} opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações do Passageiro -->
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph-id-card me-2"></i> Dados Pessoais</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" width="40%">Nome Completo</td>
                            <td><strong>{{ $passenger->first_name }} {{ $passenger->last_name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td>{{ $passenger->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Telefone</td>
                            <td class="text-success fw-semibold">{{ $passenger->phone }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tipo de Documento</td>
                            <td><span class="badge bg-secondary">{{ strtoupper($passenger->document_type) }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Número do Documento</td>
                            <td><strong>{{ $passenger->document_number }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Registado em</td>
                            <td>{{ $passenger->created_at->format('d/m/Y \à\s H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Última Atualização</td>
                            <td>{{ $passenger->updated_at->diffForHumans() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Histórico de Bilhetes -->
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center">
                    <h6 class="mb-0"><i class="ph-ticket me-2"></i> Histórico de Bilhetes</h6>
                    <span class="ms-auto badge bg-primary">{{ $tickets->total() }} bilhetes</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Bilhete</th>
                                <th>Rota</th>
                                <th>Data / Hora</th>
                                <th>Assento</th>
                                <th>Preço</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="fw-semibold text-primary">
                                            {{ $ticket->ticket_number }}
                                        </a>
                                        <br><small class="text-muted">{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <span>{{ $ticket->schedule->route->originCity->name }}</span>
                                            <i class="ph-arrow-right text-muted"></i>
                                            <span>{{ $ticket->schedule->route->destinationCity->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $ticket->schedule->departure_date->format('d/m/Y') }}
                                        <br><small class="text-muted">{{ $ticket->schedule->departure_time }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary bg-opacity-25 text-secondary">
                                            <i class="ph-armchair"></i> {{ $ticket->seat_number }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold">{{ number_format($ticket->price, 2) }} MT</td>
                                    <td class="text-center">
                                        @switch($ticket->status)
                                            @case('paid')      <span class="badge bg-success bg-opacity-10 text-success"><i class="ph-check-circle"></i> Pago</span> @break
                                            @case('validated') <span class="badge bg-info bg-opacity-10 text-info"><i class="ph-seal-check"></i> Validado</span> @break
                                            @case('reserved')  <span class="badge bg-warning bg-opacity-10 text-warning"><i class="ph-clock"></i> Reservado</span> @break
                                            @case('cancelled') <span class="badge bg-danger bg-opacity-10 text-danger"><i class="ph-x-circle"></i> Cancelado</span> @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('tickets.pdf.download', $ticket) }}" target="_blank" class="btn btn-sm btn-light" title="PDF">
                                            <i class="ph-file-pdf text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="ph-ticket ph-3x mb-3 d-block"></i>
                                        Nenhum bilhete encontrado
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
@endsection