# 🚌 Sistema de Horários - Documentação

## 📋 Visão Geral

Sistema simplificado de replicação automática de horários para o e-ticketing.

### Como Funciona:
1. **Admin cria horários manualmente** para pelo menos 1 semana
2. **Sistema replica automaticamente** esses horários para os próximos dias
3. **Passageiros sempre encontram horários** disponíveis para reservar

---

## ⚙️ Configuração

### 1. Adicionar ao `.env`:

```env
# Ativar replicação automática
SCHEDULE_AUTO_REPLICATION=true

# Quantos dias à frente replicar (padrão: 3)
SCHEDULE_DAYS_AHEAD=3

# Horário de execução (padrão: 01:00)
SCHEDULE_RUN_AT=01:00

# Quantos dias de antecedência passageiros podem reservar
SCHEDULE_BOOKING_ADVANCE_DAYS=60

# Atualizar status automaticamente
SCHEDULE_AUTO_STATUS_UPDATE=true
```

### 2. Ativar o Scheduler do Laravel:

Adicionar ao crontab do servidor:

```bash
* * * * * cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🚀 Uso

### Passo 1: Criar Horários Iniciais

Criar horários manualmente para **pelo menos 1 semana**:

```
Segunda-feira:
- Maputo → Beira às 08:00
- Beira → Maputo às 14:00

Terça-feira:
- Maputo → Beira às 08:00
- (etc...)
```

### Passo 2: Sistema Replica Automaticamente

O comando roda **todos os dias à 01:00** (configurável) e:
- Pega os horários de **1 semana atrás** (mesmo dia da semana)
- Replica para **3 dias à frente** (configurável)
- Atribui autocarros disponíveis automaticamente

**Exemplo:**
- Hoje é Segunda (20/01)
- Sistema pega horários da Segunda passada (13/01)
- Replica para Segunda daqui a 3 dias (27/01)

---

## 🔧 Comandos Manuais

### Replicar Horários Manualmente:

```bash
# Replicar para 3 dias à frente (padrão)
php artisan schedules:replicate

# Replicar para 7 dias à frente
php artisan schedules:replicate --days=7

# Simular sem criar (teste)
php artisan schedules:replicate --dry-run

# Forçar replicação mesmo se já rodou hoje
php artisan schedules:replicate --force
```

### Atualizar Status de Horários:

```bash
# Marca horários como "departed" ou "full" automaticamente
php artisan schedules:update-status
```

---

## 📊 Lógica de Replicação

### 1. **Busca Horários Fonte:**
- Usa horários de **1 semana atrás**
- Mesmo dia da semana
- Ignora horários cancelados

### 2. **Verifica Disponibilidade:**
- Checa se já existe horário na data alvo
- Verifica se autocarro está disponível
- Busca autocarro alternativo se necessário

### 3. **Cria Novo Horário:**
- Mesma rota
- Mesmo horário
- Mesmo preço
- Autocarro disponível

### 4. **Atualização de Status:**
- Marca como "departed" após 30 minutos da partida
- Marca como "full" quando todos lugares vendidos

---

## 🎯 Cenários de Uso

### Cenário 1: Operação Normal

```
Semana 1 (manual):
- Seg 13/01: Maputo→Beira 08:00 ✅ (criado manual)
- Ter 14/01: Maputo→Beira 08:00 ✅ (criado manual)
- Qua 15/01: Maputo→Beira 08:00 ✅ (criado manual)

Sistema replica automaticamente:
- Seg 20/01: Maputo→Beira 08:00 ✅ (replicado de 13/01)
- Ter 21/01: Maputo→Beira 08:00 ✅ (replicado de 14/01)
- Qua 22/01: Maputo→Beira 08:00 ✅ (replicado de 15/01)
```

### Cenário 2: Feriado ou Evento Especial

Adicionar horários extras manualmente:
```
Segunda 20/01 (feriado):
- 08:00 ✅ (replicado automaticamente)
- 10:00 ✅ (adicionar manualmente)
- 14:00 ✅ (adicionar manualmente)
```

### Cenário 3: Suspender Rota Temporariamente

1. Cancelar horários futuros manualmente
2. Sistema não replica horários cancelados
3. Reativar quando necessário

---

## 🔄 Manutenção

### Monitorar Logs:

```bash
# Ver logs do scheduler
tail -f storage/logs/laravel.log | grep "schedules:replicate"

# Verificar última execução
php artisan schedule:list
```

### Verificar Horários Criados:

```sql
SELECT 
    DATE(departure_date) as data,
    COUNT(*) as total_horarios
FROM schedules
WHERE departure_date >= CURDATE()
GROUP BY DATE(departure_date)
ORDER BY data;
```

---

## ⚠️ Avisos Importantes

1. **Criar horários iniciais:** Sistema precisa de pelo menos 1 semana de horários manuais
2. **Autocarros disponíveis:** Certifique-se de ter autocarros suficientes
3. **Crontab ativo:** Scheduler só funciona com cron configurado
4. **Backup:** Sempre fazer backup antes de mudanças importantes

---

## 🆘 Troubleshooting

### Problema: Horários não estão sendo replicados

**Soluções:**
1. Verificar se cron está ativo: `crontab -l`
2. Verificar configuração no `.env`
3. Executar manualmente: `php artisan schedules:replicate --dry-run`
4. Verificar logs: `storage/logs/laravel.log`

### Problema: Nenhum autocarro disponível

**Soluções:**
1. Adicionar mais autocarros ao sistema
2. Verificar status dos autocarros (devem estar "active")
3. Ajustar horários para evitar conflitos

### Problema: Horários duplicados

**Soluções:**
1. Sistema ignora duplicatas automaticamente
2. Verificar se comando não está rodando múltiplas vezes
3. Limpar horários futuros e replicar novamente

---

## 📈 Melhorias Futuras (Opcional)

- Dashboard com estatísticas de replicação
- Notificações quando falhar
- Sugestões de horários baseado em demanda
- Machine learning para otimizar oferta

---

## 🎉 Pronto!

O sistema está configurado e funcionando automaticamente! 🚀