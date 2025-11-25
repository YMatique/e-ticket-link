@extends('layouts.passenger')

@section('content')
<div>
    <!-- Hero Section -->
    <div class="bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center text-white">
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="ph-question ph-2x me-2"></i>
                        Central de Ajuda
                    </h1>
                    <p class="lead mb-0">
                        Encontre respostas para suas dúvidas e saiba como usar nossos serviços
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Busca Rápida -->
    <div class="container py-5">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white">
                                <i class="ph-magnifying-glass"></i>
                            </span>
                            <input type="text" class="form-control" id="searchHelp" 
                                   placeholder="Pesquisar na central de ajuda...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorias de Ajuda -->
        <div class="row mb-5">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; font-size: 36px;">
                                <i class="ph-ticket"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">Como Comprar</h4>
                        <p class="text-muted">
                            Aprenda a comprar bilhetes online de forma rápida e segura
                        </p>
                        <a href="#comprar" class="btn btn-outline-primary">Saiba Mais</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; font-size: 36px;">
                                <i class="ph-credit-card"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">Pagamentos</h4>
                        <p class="text-muted">
                            Informações sobre métodos de pagamento e reembolsos
                        </p>
                        <a href="#pagamentos" class="btn btn-outline-success">Saiba Mais</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; font-size: 36px;">
                                <i class="ph-map-pin"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">Viagens</h4>
                        <p class="text-muted">
                            Tudo sobre rotas, horários e o que levar na viagem
                        </p>
                        <a href="#viagens" class="btn btn-outline-info">Saiba Mais</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ - Como Comprar -->
        <div class="row mb-5" id="comprar">
            <div class="col-lg-12">
                <div class="section-header mb-4">
                    <h2 class="text-primary">
                        <i class="ph-shopping-cart me-2"></i>
                        Como Comprar Bilhetes
                    </h2>
                </div>

                <div class="accordion" id="accordionComprar">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#compra1" aria-expanded="true">
                                <i class="ph-number-circle-one me-2"></i>
                                Como faço para comprar um bilhete online?
                            </button>
                        </h2>
                        <div id="compra1" class="accordion-collapse collapse show" data-bs-parent="#accordionComprar">
                            <div class="accordion-body">
                                <ol class="ps-3">
                                    <li>Acesse a página inicial e selecione origem, destino e data</li>
                                    <li>Clique em "Buscar Viagens"</li>
                                    <li>Escolha o horário desejado</li>
                                    <li>Selecione seus assentos no mapa</li>
                                    <li>Preencha os dados dos passageiros</li>
                                    <li>Escolha o método de pagamento (M-Pesa, e-Mola ou Dinheiro)</li>
                                    <li>Confirme e receba seus bilhetes por email!</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#compra2">
                                <i class="ph-number-circle-two me-2"></i>
                                Preciso criar uma conta para comprar?
                            </button>
                        </h2>
                        <div id="compra2" class="accordion-collapse collapse" data-bs-parent="#accordionComprar">
                            <div class="accordion-body">
                                Não! Você pode comprar bilhetes sem criar conta. Basta fornecer seu email e telefone 
                                para receber os bilhetes. Porém, criar uma conta facilita suas próximas compras e 
                                permite acompanhar seu histórico de viagens.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#compra3">
                                <i class="ph-number-circle-three me-2"></i>
                                Posso escolher meu assento?
                            </button>
                        </h2>
                        <div id="compra3" class="accordion-collapse collapse" data-bs-parent="#accordionComprar">
                            <div class="accordion-body">
                                Sim! Nosso sistema permite que você escolha seu assento preferido no mapa visual do autocarro. 
                                Os assentos ocupados aparecem em vermelho, disponíveis em verde, e você pode selecionar 
                                quantos precisar.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#compra4">
                                <i class="ph-number-circle-four me-2"></i>
                                Quanto tempo tenho para completar a compra?
                            </button>
                        </h2>
                        <div id="compra4" class="accordion-collapse collapse" data-bs-parent="#accordionComprar">
                            <div class="accordion-body">
                                Você tem <strong>15 minutos</strong> para completar sua compra após selecionar os assentos. 
                                Um timer aparecerá na tela mostrando o tempo restante. Após esse período, os assentos 
                                ficam disponíveis novamente.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ - Pagamentos -->
        <div class="row mb-5" id="pagamentos">
            <div class="col-lg-12">
                <div class="section-header mb-4">
                    <h2 class="text-success">
                        <i class="ph-money me-2"></i>
                        Pagamentos e Reembolsos
                    </h2>
                </div>

                <div class="accordion" id="accordionPagamentos">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#pag1" aria-expanded="true">
                                Quais métodos de pagamento são aceitos?
                            </button>
                        </h2>
                        <div id="pag1" class="accordion-collapse collapse show" data-bs-parent="#accordionPagamentos">
                            <div class="accordion-body">
                                <ul>
                                    <li><strong>M-Pesa:</strong> Pagamento instantâneo via telemóvel</li>
                                    <li><strong>e-Mola:</strong> Pagamento digital rápido e seguro</li>
                                    <li><strong>Dinheiro:</strong> Pague no terminal antes do embarque</li>
                                </ul>
                                <div class="alert alert-info mt-3">
                                    <i class="ph-info me-2"></i>
                                    <strong>Dica:</strong> Pagamentos online são processados instantaneamente!
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#pag2">
                                Meu pagamento foi aprovado mas não recebi o bilhete. O que fazer?
                            </button>
                        </h2>
                        <div id="pag2" class="accordion-collapse collapse" data-bs-parent="#accordionPagamentos">
                            <div class="accordion-body">
                                <p>Não se preocupe! Siga estes passos:</p>
                                <ol>
                                    <li>Verifique sua caixa de spam/lixo eletrônico</li>
                                    <li>Aguarde até 10 minutos (pode haver atraso no email)</li>
                                    <li>Acesse "Meus Bilhetes" e busque por seu email ou telefone</li>
                                    <li>Se ainda não encontrar, entre em contato conosco com o número da transação</li>
                                </ol>
                                <div class="alert alert-warning mt-3">
                                    <i class="ph-warning me-2"></i>
                                    Guarde sempre o número da transação do pagamento!
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#pag3">
                                Posso cancelar minha compra e receber reembolso?
                            </button>
                        </h2>
                        <div id="pag3" class="accordion-collapse collapse" data-bs-parent="#accordionPagamentos">
                            <div class="accordion-body">
                                <p><strong>Sim!</strong> Nossa política de cancelamento:</p>
                                <ul>
                                    <li>Mais de 24 horas antes da viagem: <strong>Reembolso total</strong></li>
                                    <li>Entre 12-24 horas antes: <strong>75% de reembolso</strong></li>
                                    <li>Entre 6-12 horas antes: <strong>50% de reembolso</strong></li>
                                    <li>Menos de 6 horas antes: <strong>Sem reembolso</strong></li>
                                </ul>
                                <p class="mt-3">Entre em contato através do nosso suporte para solicitar o cancelamento.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ - Viagens -->
        <div class="row mb-5" id="viagens">
            <div class="col-lg-12">
                <div class="section-header mb-4">
                    <h2 class="text-info">
                        <i class="ph-bus me-2"></i>
                        Sobre as Viagens
                    </h2>
                </div>

                <div class="accordion" id="accordionViagens">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#viag1" aria-expanded="true">
                                Preciso imprimir o bilhete?
                            </button>
                        </h2>
                        <div id="viag1" class="accordion-collapse collapse show" data-bs-parent="#accordionViagens">
                            <div class="accordion-body">
                                <strong>Não!</strong> Você pode apresentar o bilhete de 3 formas:
                                <ul class="mt-2">
                                    <li>QR Code no telemóvel (mais prático!)</li>
                                    <li>Número do bilhete</li>
                                    <li>Bilhete impresso (opcional)</li>
                                </ul>
                                <div class="alert alert-success mt-3">
                                    <i class="ph-check-circle me-2"></i>
                                    Recomendamos salvar o QR Code no telemóvel para facilitar o embarque!
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#viag2">
                                Com quanto tempo de antecedência devo chegar?
                            </button>
                        </h2>
                        <div id="viag2" class="accordion-collapse collapse" data-bs-parent="#accordionViagens">
                            <div class="accordion-body">
                                Recomendamos chegar com <strong>30 minutos de antecedência</strong> para:
                                <ul>
                                    <li>Validar seu bilhete</li>
                                    <li>Despachar bagagem (se necessário)</li>
                                    <li>Encontrar seu assento com calma</li>
                                </ul>
                                <p class="text-danger mb-0 mt-3">
                                    <i class="ph-warning me-2"></i>
                                    <strong>Importante:</strong> O autocarro parte no horário exato. Não esperamos por atrasos!
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#viag3">
                                Quanto de bagagem posso levar?
                            </button>
                        </h2>
                        <div id="viag3" class="accordion-collapse collapse" data-bs-parent="#accordionViagens">
                            <div class="accordion-body">
                                <strong>Bagagem incluída no bilhete:</strong>
                                <ul>
                                    <li>1 mala de mão (até 10kg)</li>
                                    <li>1 mala despachada (até 20kg)</li>
                                </ul>
                                <p><strong>Bagagem adicional:</strong></p>
                                <ul>
                                    <li>Cada 10kg extras: 200 MT</li>
                                    <li>Deve ser paga no terminal antes do embarque</li>
                                </ul>
                                <div class="alert alert-warning mt-3">
                                    <i class="ph-warning me-2"></i>
                                    Itens proibidos: armas, explosivos, drogas, animais (exceto com autorização)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#viag4">
                                O que levar para a viagem?
                            </button>
                        </h2>
                        <div id="viag4" class="accordion-collapse collapse" data-bs-parent="#accordionViagens">
                            <div class="accordion-body">
                                <strong>Documentos obrigatórios:</strong>
                                <ul>
                                    <li>BI, Passaporte ou Certidão de Nascimento (menores)</li>
                                    <li>Bilhete (físico ou digital)</li>
                                </ul>
                                <strong>Recomendamos levar:</strong>
                                <ul>
                                    <li>Água e snacks</li>
                                    <li>Medicamentos pessoais</li>
                                    <li>Carregador de telemóvel</li>
                                    <li>Agasalho (ar condicionado pode ser frio)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contato -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-5 text-white">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold mb-3">
                                <i class="ph-headset me-2"></i>
                                Ainda precisa de ajuda?
                            </h2>
                            <p class="lead">Nossa equipe está pronta para atendê-lo!</p>
                        </div>

                        <div class="row g-4 mt-3">
                            <div class="col-md-4">
                                <div class="text-center p-4 bg-white bg-opacity-10 rounded">
                                    <i class="ph-phone ph-3x mb-3"></i>
                                    <h5>Telefone</h5>
                                    <a href="tel:+258840000000" class="text-white text-decoration-none">
                                        +258 84 000 0000
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="text-center p-4 bg-white bg-opacity-10 rounded">
                                    <i class="ph-whatsapp-logo ph-3x mb-3"></i>
                                    <h5>WhatsApp</h5>
                                    <a href="https://wa.me/258840000000" class="text-white text-decoration-none" target="_blank">
                                        +258 84 000 0000
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="text-center p-4 bg-white bg-opacity-10 rounded">
                                    <i class="ph-envelope ph-3x mb-3"></i>
                                    <h5>Email</h5>
                                    <a href="mailto:suporte@citylink.co.mz" class="text-white text-decoration-none">
                                        suporte@citylink.co.mz
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p class="mb-0">Horário de atendimento: Segunda a Domingo, 6h às 22h</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .hover-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
        }

        .accordion-button:not(.collapsed) {
            background-color: #667eea;
            color: white;
        }

        .section-header h2 {
            padding-bottom: 15px;
            border-bottom: 3px solid currentColor;
        }
    </style>

    <script>
        // Busca simples no FAQ
        document.getElementById('searchHelp')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const accordionItems = document.querySelectorAll('.accordion-item');
            
            accordionItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm) || searchTerm === '') {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</div>
@endsection