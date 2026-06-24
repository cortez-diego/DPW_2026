<style>
    .calendar-container {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 24px;
        margin-bottom: 24px;
        position: relative;
        z-index: 100;
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .calendar-header h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: #4F4F4F;
        margin: 0;
    }

    .calendar-nav {
        display: flex;
        gap: 8px;
    }

    .calendar-nav button {
        background: #6FCF97;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .calendar-nav button:hover {
        background: #58b87e;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }

    .calendar-day-header {
        text-align: center;
        font-weight: 600;
        color: #888;
        padding: 12px;
        font-family: 'Inter', sans-serif;
    }

    .calendar-day {
        min-height: 100px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
    }

    .calendar-day:hover {
        border-color: #6FCF97;
        background: #f8fff8;
    }

    .calendar-day.today {
        background: #e8f5ef;
        border-color: #6FCF97;
    }

    .calendar-day.empty {
        background: #f5f5f5;
        cursor: default;
    }

    .calendar-day.empty:hover {
        border-color: #e0e0e0;
        background: #f5f5f5;
    }

    .calendar-day-number {
        font-weight: 600;
        color: #4F4F4F;
        margin-bottom: 4px;
    }

    .calendar-day-procedimento {
        background: #6FCF97;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        margin-bottom: 4px;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .calendar-day-procedimento:hover {
        background: #58b87e;
    }

    .procedimentos-list {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 24px;
    }

    .procedimentos-list h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: #4F4F4F;
        margin-bottom: 16px;
    }

    .procedimento-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .procedimento-item:last-child {
        border-bottom: none;
    }

    .procedimento-animal-photo {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        background: #e0e0e0;
    }

    .procedimento-info {
        flex: 1;
    }

    .procedimento-info h4 {
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: #4F4F4F;
        margin: 0 0 4px 0;
    }

    .procedimento-info p {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #888;
        margin: 0;
    }

    .procedimento-status {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .procedimento-status.agendado {
        background: #e8f5ef;
        color: #6FCF97;
    }

    .procedimento-status.em_andamento {
        background: #fff8f0;
        color: #f5c98a;
    }

    .procedimento-status.concluido {
        background: #e8f0ff;
        color: #56CCF2;
    }

    .procedimento-status.cancelado {
        background: #ffe8e8;
        color: #f5a0a0;
    }

    .btn-agendar {
        background: #6FCF97;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        font-family: 'Poppins', sans-serif;
    }

    .btn-agendar:hover {
        background: #58b87e;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .modal-header h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 20px;
        font-weight: 700;
        color: #4F4F4F;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #888;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: #4F4F4F;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1.5px solid #e0e0e0;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #4F4F4F;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #6FCF97;
    }

    .btn-salvar {
        background: #6FCF97;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        font-family: 'Poppins', sans-serif;
        width: 100%;
    }

    .btn-salvar:hover {
        background: #58b87e;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="calendar-container">
                <div class="calendar-header">
                    <h2 id="currentMonth">Janeiro 2024</h2>
                    <div class="calendar-nav">
                        <button onclick="changeMonth(-1)">← Anterior</button>
                        <button onclick="changeMonth(1)">Próximo →</button>
                    </div>
                </div>
                <div class="calendar-grid">
                    <div class="calendar-day-header">Dom</div>
                    <div class="calendar-day-header">Seg</div>
                    <div class="calendar-day-header">Ter</div>
                    <div class="calendar-day-header">Qua</div>
                    <div class="calendar-day-header">Qui</div>
                    <div class="calendar-day-header">Sex</div>
                    <div class="calendar-day-header">Sáb</div>
                </div>
                <div class="calendar-grid" id="calendarDays"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="procedimentos-list">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3>Procedimentos do Dia</h3>
                    <button class="btn-agendar" onclick="openModal()">+ Agendar Procedimento</button>
                </div>
                <div id="procedimentosList">
                    <?php if (empty($this->procedimentos)): ?>
                        <p style="color: #888; text-align: center; padding: 24px;">Nenhum procedimento agendado para este mês.</p>
                    <?php else: ?>
                        <?php foreach ($this->procedimentos as $procedimento): ?>
                            <div class="procedimento-item">
                                <img src="<?php echo $procedimento['animal_foto'] ?? 'https://via.placeholder.com/60'; ?>" 
                                     alt="<?php echo htmlspecialchars($procedimento['animal_nome']); ?>" 
                                     class="procedimento-animal-photo">
                                <div class="procedimento-info">
                                    <h4><?php echo htmlspecialchars($procedimento['animal_nome']); ?></h4>
                                    <p><?php echo htmlspecialchars($procedimento['tipo_procedimento']); ?></p>
                                    <p><?php echo date('d/m/Y H:i', strtotime($procedimento['data_hora'])); ?></p>
                                </div>
                                <span class="procedimento-status <?php echo $procedimento['status']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $procedimento['status'])); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agendar procedimento -->
<div class="modal" id="agendarModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Agendar Procedimento</h3>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <form id="agendarForm">
            <div class="form-group">
                <label for="animal">Animal</label>
                <select id="animal" name="fk_animal_id" required>
                    <option value="">Selecione um animal</option>
                    <?php foreach ($this->animais as $animal): ?>
                        <option value="<?php echo $animal['id']; ?>">
                            <?php echo htmlspecialchars($animal['nome']); ?> (<?php echo htmlspecialchars($animal['especie']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tipo_procedimento">Tipo de Procedimento</label>
                <select id="tipo_procedimento" name="tipo_procedimento" required>
                    <option value="">Selecione o tipo</option>
                    <option value="Consulta">Consulta</option>
                    <option value="Vacinação">Vacinação</option>
                    <option value="Cirurgia">Cirurgia</option>
                    <option value="Exame">Exame</option>
                    <option value="Tratamento">Tratamento</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>
            <div class="form-group">
                <label for="data_hora">Data e Hora</label>
                <input type="datetime-local" id="data_hora" name="data_hora" required>
            </div>
            <div class="form-group">
                <label for="duracao_minutos">Duração (minutos)</label>
                <input type="number" id="duracao_minutos" name="duracao_minutos" value="30" min="15" step="15">
            </div>
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes" rows="2"></textarea>
            </div>
            <button type="submit" class="btn-salvar">Agendar</button>
        </form>
    </div>
</div>

<script>
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    const procedimentos = <?php echo json_encode($this->procedimentos); ?>;

    function renderCalendar() {
        const monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
                           'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        
        document.getElementById('currentMonth').textContent = `${monthNames[currentMonth]} ${currentYear}`;
        
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        const calendarDays = document.getElementById('calendarDays');
        calendarDays.innerHTML = '';
        
        // Empty cells for days before the first day of the month
        for (let i = 0; i < firstDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day empty';
            calendarDays.appendChild(emptyDay);
        }
        
        // Days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            
            const today = new Date();
            if (day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
                dayElement.classList.add('today');
            }
            
            const dayNumber = document.createElement('div');
            dayNumber.className = 'calendar-day-number';
            dayNumber.textContent = day;
            dayElement.appendChild(dayNumber);
            
            // Add procedimentos for this day
            const dayProcedimentos = procedimentos.filter(p => {
                const procDate = new Date(p.data_hora);
                return procDate.getDate() === day && 
                       procDate.getMonth() === currentMonth && 
                       procDate.getFullYear() === currentYear;
            });
            
            dayProcedimentos.forEach(proc => {
                const procElement = document.createElement('div');
                procElement.className = 'calendar-day-procedimento';
                procElement.textContent = `${proc.tipo_procedimento} - ${new Date(proc.data_hora).toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}`;
                procElement.onclick = (e) => {
                    e.stopPropagation();
                    alert(`${proc.tipo_procedimento}\nAnimal: ${proc.animal_nome}\nHorário: ${new Date(proc.data_hora).toLocaleString('pt-BR')}`);
                };
                dayElement.appendChild(procElement);
            });
            
            dayElement.onclick = () => {
                const date = new Date(currentYear, currentMonth, day);
                document.getElementById('data_hora').value = date.toISOString().slice(0, 16);
                openModal();
            };
            
            calendarDays.appendChild(dayElement);
        }
    }

    function changeMonth(delta) {
        currentMonth += delta;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        } else if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
        
        // Update URL with new month
        const url = new URL(window.location);
        url.searchParams.set('mes', String(currentMonth + 1).padStart(2, '0'));
        url.searchParams.set('ano', currentYear);
        window.location.href = url.toString();
    }

    function openModal() {
        document.getElementById('agendarModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('agendarModal').classList.remove('active');
    }

    document.getElementById('agendarForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        fetch('/procedimentos/criar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Procedimento agendado com sucesso!');
                closeModal();
                location.reload();
            } else {
                alert('Erro ao agendar procedimento: ' + result.message);
            }
        })
        .catch(error => {
            alert('Erro ao agendar procedimento: ' + error);
        });
    });

    // Initialize calendar
    renderCalendar();
</script>
