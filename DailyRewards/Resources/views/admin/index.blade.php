<div class="daily-rewards-admin">
    <h2>Ежедневные бонусы</h2>
    
    <form method="POST" action="/admin/dailyrewards/save" class="reward-form">
        @csrf
        <input type="hidden" name="id" value="">
        
        <div class="form-group">
            <label>День</label>
            <input type="number" name="dayNumber" class="form-control" min="1" value="" required>
        </div>
        
        <div class="form-group">
            <label>Изображение (URL)</label>
            <input type="text" name="image" class="form-control" placeholder="https://...">
        </div>
        
        <div class="form-group">
            <label>Баланс (₽)</label>
            <input type="number" name="balance" class="form-control" step="0.01" min="0" value="0" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
    
    <h3>Список дней</h3>
    <table class="table">
        <thead>
            <tr>
                <th>День</th>
                <th>Изображение</th>
                <th>Баланс</th>
                <th>Активен</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rewards as $reward)
            <tr>
                <td>{{ $reward->dayNumber }}</td>
                <td>
                    @if($reward->image)
                        <img src="{{ $reward->image }}" alt="" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <span class="text-muted">Нет</span>
                    @endif
                </td>
                <td>{{ number_format($reward->balance, 2) }} ₽</td>
                <td>
                    @if($reward->isActive)
                        <span class="badge bg-success">Да</span>
                    @else
                        <span class="badge bg-secondary">Нет</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm btn-info edit-btn" 
                            data-id="{{ $reward->id }}"
                            data-day="{{ $reward->dayNumber }}"
                            data-image="{{ $reward->image }}"
                            data-balance="{{ $reward->balance }}">Редактировать</button>
                    <a href="/admin/dailyrewards/delete/{{ $reward->id }}" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?')">Удалить</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
.daily-rewards-admin {
    padding: 20px;
}
.reward-form {
    background: #2a2a3e;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #fff;
}
.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #3f3f46;
    border-radius: 4px;
    background: #1e1e2e;
    color: #fff;
}
.table {
    width: 100%;
    border-collapse: collapse;
}
.table th, .table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #3f3f46;
    color: #fff;
}
.table th {
    background: #2a2a3e;
}
.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-primary {
    background: #4ade80;
    color: #000;
}
.btn-info {
    background: #3b82f6;
    color: #fff;
}
.btn-danger {
    background: #ef4444;
    color: #fff;
}
.badge {
    padding: 4px 8px;
    border-radius: 4px;
}
.bg-success {
    background: #22c55e;
}
.bg-secondary {
    background: #6b7280;
}
</style>

<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const form = document.querySelector('.reward-form');
        form.querySelector('input[name="id"]').value = this.dataset.id;
        form.querySelector('input[name="dayNumber"]').value = this.dataset.day;
        form.querySelector('input[name="image"]').value = this.dataset.image || '';
        form.querySelector('input[name="balance"]').value = this.dataset.balance;
        form.querySelector('button[type="submit"]').textContent = 'Сохранить';
    });
});
</script>
