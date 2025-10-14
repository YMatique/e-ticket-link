@extends('layouts.app')

@section('title', 'Cidades')

@section('breadcrumbs')
    <span class="breadcrumb-item active">Localização</span>
    <span class="breadcrumb-item active">Cidades</span>
@endsection

@section('page-title', 'Cidades')

@section('header-actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCityModal">
        <i class="ph-plus me-2"></i>
        Nova Cidade
    </button>
@endsection

@section('content')
    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('cities.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Buscar por nome</label>
                    <input type="text" name="search" class="form-control" placeholder="Digite o nome da cidade" value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filtrar por província</label>
                    <select name="province_id" class="form-select">
                        <option value="">Todas as províncias</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="ph-funnel me-2"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('cities.index') }}" class="btn btn-light">
                        <i class="ph-x me-2"></i>
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Cities Table -->
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0">Lista de Cidades</h5>
            <div class="ms-auto">
                <span class="badge bg-primary">{{ $cities->total() }} registros</span>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Nome</th>
                            <th style="width: 200px;">Província</th>
                            <th style="width: 180px;">Data Criação</th>
                            <th style="width: 200px;" class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                            <tr>
                                <td>{{ $city->id }}</td>
                                <td class="fw-semibold">{{ $city->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $city->province->name }}</span>
                                </td>
                                <td>{{ $city->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('cities.show', $city) }}" 
                                           class="btn btn-light btn-sm" 
                                           title="Ver detalhes">
                                            <i class="ph-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-light btn-sm" 
                                                onclick="editCity({{ $city->id }}, '{{ $city->name }}', {{ $city->province_id }})"
                                                title="Editar">
                                            <i class="ph-pencil"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-light btn-sm text-danger" 
                                                onclick="deleteCity({{ $city->id }}, '{{ $city->name }}')"
                                                title="Excluir">
                                            <i class="ph-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ph-info ph-2x d-block mb-2"></i>
                                        Nenhuma cidade encontrada.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($cities->hasPages())
            <div class="card-footer">
                {{ $cities->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Create City Modal -->
    <div class="modal fade" id="createCityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Cidade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createCityForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome da Cidade <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Província <span class="text-danger">*</span></label>
                            <select name="province_id" class="form-select" required>
                                <option value="">Selecione uma província</option>
                                @foreach($provinces as $province)
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
                            Salvar
                        </button>
                    </div>
                </form>
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
                                @foreach($provinces as $province)
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
// Criar Cidade
document.getElementById('createCityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    fetch('{{ route("cities.store") }}', {
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
        showToast('error', 'Erro ao criar cidade');
        submitBtn.disabled = false;
    });
});

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

// Deletar Cidade
function deleteCity(id, name) {
    if (!confirm(`Tem certeza que deseja excluir a cidade "${name}"?`)) {
        return;
    }
    
    fetch(`/cities/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        showToast('error', 'Erro ao excluir cidade');
    });
}
</script>
@endpush