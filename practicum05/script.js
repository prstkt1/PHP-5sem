const results = document.getElementById('results');
const searchInput = document.getElementById('search');
const addForm = document.getElementById('add-form');

function showMessage(text, className = 'message') {
    results.innerHTML = '';
    const div = document.createElement('div');
    div.className = className;
    div.textContent = text;
    results.appendChild(div);
}

function createCard(habit) {
    const card = document.createElement('div');
    card.className = 'card';
    card.dataset.id = habit.id;

    const info = document.createElement('div');
    const title = document.createElement('h3');
    title.textContent = habit.name;
    const target = document.createElement('div');
    target.textContent = 'Ціль: ' + habit.target_per_week + ' раз/тиждень';
    const streak = document.createElement('div');
    streak.textContent = 'Серія: ';
    const streakValue = document.createElement('span');
    streakValue.className = 'streak';
    streakValue.textContent = habit.current_streak;
    streak.appendChild(streakValue);
    info.append(title, target, streak);

    const button = document.createElement('button');
    button.textContent = 'Відмітити виконання';
    button.addEventListener('click', () => checkin(habit.id, streakValue));

    card.append(info, button);
    return card;
}

function render(habits) {
    results.innerHTML = '';
    if (habits.length === 0) {
        showMessage('Звичок не знайдено', 'empty');
        return;
    }
    habits.forEach(h => results.appendChild(createCard(h)));
}

async function loadList(term = '') {
    try {
        const response = await fetch('api_list.php?q=' + encodeURIComponent(term));
        if (!response.ok) {
            throw new Error('Сервер повернув помилку ' + response.status);
        }
        render(await response.json());
    } catch (err) {
        showMessage('Не вдалося завантажити список: ' + err.message);
    }
}

searchInput.addEventListener('input', () => loadList(searchInput.value.trim()));

addForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    const formData = new FormData(addForm);
    const payload = {
        name: formData.get('name'),
        target_per_week: Number(formData.get('target_per_week')),
    };

    try {
        const response = await fetch('api_add.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.error || 'Помилка ' + response.status);
        }
        addForm.reset();
        searchInput.value = '';
        await loadList();
    } catch (err) {
        showMessage('Не вдалося додати звичку: ' + err.message);
    }
});

async function checkin(id, streakElement) {
    try {
        const response = await fetch('api_checkin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id }),
        });
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.error || 'Помилка ' + response.status);
        }
        streakElement.textContent = data.current_streak;
    } catch (err) {
        showMessage('Не вдалося відмітити виконання: ' + err.message);
    }
}

loadList();