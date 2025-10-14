@extends('layouts.app')

@section('title', 'Detalhes da Cidade')

@section('breadcrumbs')
    <a href="{{ route('cities.index') }}" class="breadcrumb-item">Localização</a>
    <a href="{{ route('cities.index') }}" class="breadcrumb-item">Cidades</a>
    <span class="breadcrumb-item active">{{ $city->name }}</span>
@endsection

@section('page-title', $city->name)

@section('header-actions')
    <a href="{{ route('cities.index') }}" class="btn btn-light">
        <i class="ph-arrow-left me-2"></i>
        Voltar
    </a>
    <button type="button" class="btn btn-primary" onclick="editCity({{ $city->id }}, '{{ $city->name }}', {{ $city->province_id }})">
        <i class="ph-pencil me-2"></i>
        Editar
    </button>
@endsection

@section('content')
    <!-- Info Cards -->
    <div class="row mb-3">
        <div class="col-lg-4 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h3 class="mb-0">{{ $stats['total_origin_routes'] }}</h3>
                        <span class="text-muted">Rotas de Origem</span>
                    </div>
                    <i class="ph-arrow-circle-right ph-3x text-success opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h3 class="mb-0">{{ $stats['total_destination_routes'] }}</h3>
                        <span class="text-muted">Rotas de Destino</span>
                    </div>
                    <i class="ph-arrow-circle-left ph-3x text-info opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h3 class="mb-0">{{ $stats['total_routes'] }}</h3>
                        <span class="text-muted">Total de Rotas</span>
                    </div>
                    <i class="ph-map-trifold ph-3x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações Gerais -->
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-info me-2"></i>
                        Informações Gerais
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" style="width: 40%;">ID:</td>
                            <td class="fw-semibold">{{ $city->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nome:</td>
                            <td class="fw-semibold">{{ $city->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Província:</td>
                            <td>
                                <a href="{{ route('provinces.show', $city->province) }}" class="badge bg-secondary text-decoration-none">
                                    {{ $city->province->name }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nome Completo:</td>
                            <td class="fw-semibold">{{ $city->fullName() }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Criado em:</td>
                            <td>{{ $city->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Atualizado em:</td>
                            <td>{{ $city->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Rotas -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-map-trifold me-2"></i>
                        Informações de Rotas
                    </h5>
                </div>
                <div class="card-body">
                    @if($stats['total_routes'] > 0)
                        <div class="alert alert-info border-0">
                            <div class="d-flex align-items-center">
                                <i class="ph-info ph-2x me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Rotas Associadas</h6>
                                    <p class="mb-0">
                                        Esta cidade possui <strong>{{ $stats['total_routes'] }} rota(s)</strong> cadastrada(s).
                                    </p>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>{{ $stats['total_origin_routes'] }}</strong> como origem</li>
                                        <li><strong>{{ $stats['total_destination_routes'] }}</strong> como destino</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('routes.index') }}" class="btn btn-primary">
                                <i class="ph-eye me-2"></i>
                                Ver todas as rotas
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="ph-warning ph-3x d-block mb-3 text-warning"></i>
                            <h6>Nenhuma rota cadastrada</h6>
                            <p class="mb-3">Esta cidade ainda não possui rotas de origem ou destino cadastradas.</p>
                            <a href="{{ route('routes.create') }}" class="btn btn-primary">
                                <i class="ph-plus me-2"></i>
                                Cadastrar Rota
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ação Rápida -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="ph-lightning me-2"></i>
                        Ações Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('routes.create') }}" class="btn btn-outline-primary">
                            <i class="ph-plus-circle me-2"></i>
                            Criar Nova Rota com esta Cidade
                        </a>
                        <a href="{{ route('schedules.create') }}" class="btn btn-outline-success">
                            <i class="ph-calendar-plus me-2"></i>
                            Criar Horário de Viagem
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit City Modal -->
    <div class="modal fade" id="editCityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Cidade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCityForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_city_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome da Cidade <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Província <span class="text-danger">*</span></label>
                            <select name="province_id" id="edit_province_id" class="form-select" required>
                                <option value="">Selecione uma província</option>
                                @foreach(\App\Models\Province::orderBy('name')->get() as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-check me-2"></i>
                            Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Editar Cidade
function editCity(id, name, provinceId) {
    document.getElementById('edit_city_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_province_id').value = provinceId;
    
    const modal = new bootstrap.Modal(document.getElementById('editCityModal'));
    modal.show();
}

document.getElementById('editCityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('edit_city_id').value;
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    fetch(`/cities/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', data.message);
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        showToast('error', 'Erro ao atualizar cidade');
        submitBtn.disabled = false;
    });
});
</script>
@endpush