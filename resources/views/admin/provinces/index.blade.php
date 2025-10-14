@extends('layouts.app')

@section('title', 'Províncias')

@section('breadcrumbs')
    <span class="breadcrumb-item active">Localização</span>
    <span class="breadcrumb-item active">Províncias</span>
@endsection

@section('page-title', 'Províncias')

@section('header-actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProvinceModal">
        <i class="ph-plus me-2"></i>
        Nova Província
    </button>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row mb-3">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ $provinces->total() }}</h4>
                        <span class="text-muted">Total de Províncias</span>
                    </div>
                    <i class="ph-map-pin ph-2x text-primary opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">{{ $provinces->sum('cities_count') }}</h4>
                        <span class="text-muted">Total de Cidades</span>
                    </div>
                    <i class="ph-buildings ph-2x text-success opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Provinces Table -->
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0">Lista de Províncias</h5>
            <div class="ms-auto">
                <span class="badge bg-primary">{{ $provinces->total() }} registros</span>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Nome</th>
                            <th style="width: 120px;">Código</th>
                            <th style="width: 150px;">Cidades</th>
                            <th style="width: 180px;">Data Criação</th>
                            <th style="width: 200px;" class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($provinces as $province)
                            <tr>
                                <td>{{ $province->id }}</td>
                                <td class="fw-semibold">{{ $province->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $province->code }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $province->cities_count }} cidade(s)</span>
                                </td>
                                <td>{{ $province->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('provinces.show', $province) }}" 
                                           class="btn btn-light btn-sm" 
                                           title="Ver detalhes">
                                            <i class="ph-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-light btn-sm" 
                                                onclick="editProvince({{ $province->id }}, '{{ $province->name }}', '{{ $province->code }}')"
                                                title="Editar">
                                            <i class="ph-pencil"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-light btn-sm text-danger" 
                                                onclick="deleteProvince({{ $province->id }}, '{{ $province->name }}')"
                                                title="Excluir">
                                            <i class="ph-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ph-info ph-2x d-block mb-2"></i>
                                        Nenhuma província cadastrada.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($provinces->hasPages())
            <div class="card-footer">
                {{ $provinces->links() }}
            </div>
        @endif
    </div>

    <!-- Create Province Modal -->
    <div class="modal fade" id="createProvinceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Província</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createProvinceForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome da Província <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Código <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="Ex: MP, SF" maxlength="10" required style="text-transform: uppercase;">
                            <small class="form-text text-muted">Código único com 2-10 letras maiúsculas</small>
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

    <!-- Edit Province Modal -->
    <div class="modal fade" id="editProvinceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Província</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editProvinceForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_province_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome da Província <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Código <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="edit_code" class="form-control" maxlength="10" required style="text-transform: uppercase;">
                            <small class="form-text text-muted">Código único com 2-10 letras maiúsculas</small>
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
// Criar Província
document.getElementById('createProvinceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    fetch('{{ route("provinces.store") }}', {
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
        showToast('error', 'Erro ao criar província');
        submitBtn.disabled = false;
    });
});

// Editar Província
function editProvince(id, name, code) {
    document.getElementById('edit_province_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_code').value = code;
    
    const modal = new bootstrap.Modal(document.getElementById('editProvinceModal'));
    modal.show();
}

document.getElementById('editProvinceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('edit_province_id').value;
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    fetch(`/provinces/${id}`, {
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
        showToast('error', 'Erro ao atualizar província');
        submitBtn.disabled = false;
    });
});

// Deletar Província
function deleteProvince(id, name) {
    if (!confirm(`Tem certeza que deseja excluir a província "${name}"?`)) {
        return;
    }
    
    fetch(`/provinces/${id}`, {
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
        showToast('error', 'Erro ao excluir província');
    });
}

// Uppercase automático no código
document.querySelectorAll('input[name="code"]').forEach(input => {
    input.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
@endpush