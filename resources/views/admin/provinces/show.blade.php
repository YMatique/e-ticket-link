@extends('layouts.app')

@section('title', 'Detalhes da Província')

@section('breadcrumbs')
    <a href="{{ route('provinces.index') }}" class="breadcrumb-item">Localização</a>
    <a href="{{ route('provinces.index') }}" class="breadcrumb-item">Províncias</a>
    <span class="breadcrumb-item active">{{ $province->name }}</span>
@endsection

@section('page-title', $province->name)

@section('header-actions')
    <a href="{{ route('provinces.index') }}" class="btn btn-light">
        <i class="ph-arrow-left me-2"></i>
        Voltar
    </a>
    <button type="button" class="btn btn-primary" onclick="editProvince({{ $province->id }}, '{{ $province->name }}', '{{ $province->code }}')">
        <i class="ph-pencil me-2"></i>
        Editar
    </button>
@endsection

@section('content')
    <!-- Info Cards -->
    <div class="row mb-3">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h3 class="mb-0">{{ $stats['total_cities'] }}</h3>
                        <span class="text-muted">Cidades</span>
                    </div>
                    <i class="ph-buildings ph-3x text-primary opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
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
        <div class="col-lg-3 col-sm-6">
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
        <div class="col-lg-3 col-sm-6">
            <div class="card card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h3 class="mb-0">{{ $stats['total_origin_routes'] + $stats['total_destination_routes'] }}</h3>
                        <span class="text-muted">Total de Rotas</span>
                    </div>
                    <i class="ph-map-trifold ph-3x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações Gerais -->
        <div class="col-lg-4">
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
                            <td class="fw-semibold">{{ $province->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nome:</td>
                            <td class="fw-semibold">{{ $province->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Código:</td>
                            <td><span class="badge bg-secondary">{{ $province->code }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Criado em:</td>
                            <td>{{ $province->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Atualizado em:</td>
                            <td>{{ $province->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Lista de Cidades -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-buildings me-2"></i>
                        Cidades ({{ $province->cities->count() }})
                    </h5>
                    <a href="{{ route('cities.index', ['province_id' => $province->id]) }}" class="btn btn-sm btn-light ms-auto">
                        <i class="ph-eye me-1"></i>
                        Ver todas
                    </a>
                </div>
                <div class="card-body">
                    @if($province->cities->count() > 0)
                        <div class="row">
                            @foreach($province->cities as $city)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="d-flex align-items-center p-2 border rounded">
                                        <i class="ph-map-pin-line ph-2x text-muted me-2"></i>
                                        <div class="flex-fill">
                                            <a href="{{ route('cities.show', $city) }}" class="fw-semibold text-body">
                                                {{ $city->name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="ph-info ph-3x d-block mb-2"></i>
                            <p>Nenhuma cidade cadastrada nesta província.</p>
                            <a href="{{ route('cities.index') }}" class="btn btn-sm btn-primary">
                                <i class="ph-plus me-1"></i>
                                Cadastrar Cidade
                            </a>
                        </div>
                    @endif
                </div>
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

// Uppercase automático no código
document.getElementById('edit_code').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>
@endpush