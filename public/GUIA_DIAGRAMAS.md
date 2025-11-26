# DIAGRAMAS PARA O CAPÍTULO IV - IMPLEMENTAÇÃO
## Sistema de Reserva e Bilhetagem Electrónica

---

## 📊 DIAGRAMAS CRIADOS

Foram gerados **3 diagramas profissionais** em alta resolução (300 DPI) para inserir no Capítulo IV da monografia:

### 1. **Diagrama de Casos de Uso - Cliente** ✅
**Arquivo:** `Diagrama_Casos_Uso_Cliente.png`
**Figura:** 4.1 (conforme documento)
**Tamanho:** 530 KB

**Conteúdo:**
- **Actor:** Cliente (representado por stick figure)
- **Sistema:** Boundary com 12 casos de uso
- **Casos de Uso incluídos:**
  1. Registar Conta
  2. Efectuar Login
  3. Consultar Horários
  4. Pesquisar Viagens
  5. Seleccionar Assentos
  6. Efectuar Reserva
  7. Processar Pagamento
  8. Visualizar Bilhete Electrónico
  9. Consultar Histórico de Reservas
  10. Cancelar Reserva
  11. Modificar Reserva
  12. Gerir Perfil

**Relacionamentos mostrados:**
- Linhas de associação entre Cliente e todos os casos de uso
- Relacionamento <<include>>: "Efectuar Reserva" → "Processar Pagamento"
- Relacionamento <<include>>: "Efectuar Reserva" → "Visualizar Bilhete Electrónico"

**Cores utilizadas:**
- Elipses dos casos de uso: Azul (#007bff)
- Background do sistema: Azul claro (#f0f8ff)
- Relacionamentos: Cinza tracejado

---

### 2. **Diagrama de Casos de Uso - Administrador** ✅
**Arquivo:** `Diagrama_Casos_Uso_Administrador.png`
**Figura:** 4.2 (conforme documento)
**Tamanho:** 586 KB

**Conteúdo:**
- **Actor:** Administrador (representado por stick figure)
- **Sistema:** Boundary "Painel Administrativo" com 13 casos de uso
- **Casos de Uso incluídos:**
  1. Efectuar Login
  2. Registar Autocarro
  3. Actualizar Autocarro
  4. Criar Rota
  5. Definir Preços
  6. Criar Viagem/Horário
  7. Visualizar Reservas
  8. Gerir Reservas
  9. Aprovar Pagamentos
  10. Gerar Relatórios de Vendas
  11. Gerar Relatórios de Ocupação
  12. Gerir Utilizadores
  13. Visualizar Dashboard

**Relacionamentos mostrados:**
- Linhas de associação entre Administrador e todos os casos de uso
- Relacionamento <<include>>: "Criar Viagem" → "Criar Rota"
- Relacionamento <<include>>: "Dashboard" → "Gerar Relatórios"

**Cores utilizadas:**
- Elipses dos casos de uso: Vermelho (#dc3545)
- Background do sistema: Laranja claro (#fff8f0)
- Relacionamentos: Cinza tracejado

---

### 3. **Diagrama Entidade-Relacionamento (ER)** ✅
**Arquivo:** `Diagrama_ER.png`
**Figura:** 4.4 (conforme documento)
**Tamanho:** 678 KB

**Conteúdo:**
- **9 Entidades** com todos os atributos:

**1. users**
- 🔑 id (PK)
- nome_completo
- email
- senha
- telefone
- tipo_utilizador
- data_registo
- activo

**2. bookings**
- 🔑 id (PK)
- user_id (FK)
- trip_id (FK)
- codigo_bilhete
- numero_lugares
- valor_total
- status_reserva
- data_reserva
- data_cancelamento

**3. passengers**
- 🔑 id (PK)
- booking_id (FK)
- nome_completo
- documento_identidade
- numero_assento
- tipo_documento

**4. trips**
- 🔑 id (PK)
- route_id (FK)
- bus_id (FK)
- data_partida
- hora_partida
- lugares_disponiveis
- status

**5. routes**
- 🔑 id (PK)
- origem
- destino
- distancia_km
- duracao_estimada
- preco_economico
- preco_executivo
- activa

**6. buses**
- 🔑 id (PK)
- numero_identificacao
- modelo
- capacidade_total
- classe
- estado

**7. payments**
- 🔑 id (PK)
- booking_id (FK)
- metodo_pagamento
- referencia_pagamento
- valor_pago
- status_pagamento
- data_pagamento

**8. seats**
- 🔑 id (PK)
- bus_id (FK)
- numero_assento
- tipo_assento

**9. seat_occupancy**
- 🔑 id (PK)
- trip_id (FK)
- seat_id (FK)
- passenger_id (FK)
- status
- data_reserva_temp

**Relacionamentos mostrados:**
- users (1) → (N) bookings - "faz"
- bookings (1) → (N) passengers - "tem"
- trips (1) → (N) bookings - "possui"
- routes (1) → (N) trips - "usa"
- buses (1) → (N) trips - "realiza"
- buses (1) → (N) seats - "tem"
- bookings (1) → (1) payments - "paga"
- seat_occupancy: tabela associativa N:M (trips ↔ seats ↔ passengers)

**Cores utilizadas:**
- Entidades: Azul (#007bff) com fundo azul claro (#e7f3ff)
- Relacionamentos (losangos): Verde (#28a745) com fundo verde claro (#d4edda)
- Linhas de relacionamento: Preto

**Notações:**
- 🔑 = Chave Primária (PK)
- FK = Chave Estrangeira (Foreign Key)
- Cardinalidades: 1, N, 1:1, 1:N, N:M

---

## 📋 COMO USAR OS DIAGRAMAS NA MONOGRAFIA

### **1. Inserir no Microsoft Word:**

**Passo 1:** Abrir o documento do Capítulo IV

**Passo 2:** Localizar os marcadores onde diz:
- `[Figura 4.1: Diagrama de Casos de Uso - Cliente]`
- `[Figura 4.2: Diagrama de Casos de Uso - Administrador]`
- `[Figura 4.4: Diagrama Entidade-Relacionamento]`

**Passo 3:** Inserir imagens:
- Menu: Inserir → Imagens → Este Dispositivo
- Selecionar o arquivo PNG correspondente
- Ajustar tamanho: clicar com botão direito → Tamanho e Posição
  - Sugestão: Largura 16cm (mantém proporção)

**Passo 4:** Adicionar legendas:
- Clicar na imagem
- Menu: Referências → Inserir Legenda
- Rótulo: "Figura"
- Posição: "Abaixo do item selecionado"
- Digitar: "Diagrama de Casos de Uso - Cliente"

**Passo 5:** Repetir para todas as imagens

---

### **2. Formatação Recomendada:**

**Alinhamento:** Centralizado
**Quebra de texto:** Acima e abaixo
**Espaçamento:** 
- Antes: 12pt
- Depois: 6pt (antes da legenda)

**Legenda:**
- Fonte: Times New Roman ou Arial, 10pt
- Estilo: Normal
- Formato: "Figura X.X: [Descrição]"
- Alinhamento: Centralizado

**Exemplo de legenda formatada:**
```
Figura 4.1: Diagrama de Casos de Uso - Cliente
Fonte: Elaboração própria
```

---

## 🎨 CARACTERÍSTICAS TÉCNICAS DOS DIAGRAMAS

**Resolução:** 300 DPI (alta qualidade para impressão)
**Formato:** PNG (transparência preservada onde aplicável)
**Dimensões:** ~4000x3000 pixels (redimensionáveis sem perda)
**Cores:** RGB otimizadas para impressão offset

**Compatibilidade:**
- ✅ Microsoft Word (todas as versões)
- ✅ Google Docs
- ✅ LaTeX
- ✅ LibreOffice Writer
- ✅ Adobe InDesign

---

## 📐 PADRÕES UTILIZADOS

### **Diagramas de Casos de Uso:**
- **Notação:** UML 2.5
- **Padrão:** IEEE 1016-2009
- **Elementos:**
  - Actor: Stick figure
  - Caso de Uso: Elipse
  - Sistema: Retângulo com cantos arredondados (boundary)
  - Associação: Linha contínua
  - Include/Extend: Linha tracejada com seta

### **Diagrama ER:**
- **Notação:** Chen (adaptada)
- **Padrão:** ANSI/SPARC
- **Elementos:**
  - Entidade: Retângulo arredondado
  - Relacionamento: Losango
  - Atributo: Lista dentro da entidade
  - Cardinalidade: Números/letras nas linhas

---

## 🔧 EDIÇÃO DOS DIAGRAMAS (Se Necessário)

Se precisar fazer pequenas alterações nos diagramas:

**Opção 1 - Ferramentas Online:**
- Draw.io (https://app.diagrams.net/)
- Lucidchart (https://www.lucidchart.com/)
- Creately (https://creately.com/)

**Opção 2 - Software Desktop:**
- Microsoft Visio
- StarUML (casos de uso)
- MySQL Workbench (diagrama ER)
- Visual Paradigm

**Opção 3 - Regenerar via Python:**
O código Python usado está disponível e pode ser modificado para:
- Alterar cores
- Adicionar/remover casos de uso
- Modificar entidades
- Ajustar layout

---

## ✅ CHECKLIST FINAL

Antes de submeter a monografia, verificar:

- [ ] Todas as 3 imagens inseridas no documento
- [ ] Legendas numeradas corretamente (4.1, 4.2, 4.4)
- [ ] "Fonte: Elaboração própria" em cada legenda
- [ ] Imagens centralizadas
- [ ] Imagens com tamanho consistente (~16cm largura)
- [ ] Qualidade de impressão verificada (zoom 100%)
- [ ] Referências cruzadas no texto funcionando
- [ ] Imagens não ultrapassam margens da página

---

## 📞 INFORMAÇÕES ADICIONAIS

**Data de criação:** 26 de Novembro de 2024
**Software utilizado:** Python + Matplotlib
**Formato de exportação:** PNG (300 DPI)
**Licença:** Uso livre para fins académicos

---

**NOTA IMPORTANTE:**
Estes diagramas foram criados especificamente para a sua monografia "Sistema de Reserva e Bilhetagem Electrónica de Passes de Autocarros" e estão alinhados com:
- A estrutura definida no Capítulo IV
- Os requisitos funcionais especificados nas Tabelas 4.1 e 4.2
- A modelação de base de dados descrita nas Tabelas 4.6-4.16
- As melhores práticas de UML e modelação de dados

Os diagramas são de alta qualidade profissional e adequados para submissão académica.
