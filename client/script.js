// client/script.js
const API_URL = '/CalisthenicsAPI/server';

// ============ ОБЩИЕ ФУНКЦИИ ============
async function apiRequest(endpoint, method = 'GET', data = null) {
    const options = { method: method, headers: { 'Content-Type': 'application/json' } };
    if (data && (method === 'POST' || method === 'PUT')) {
        options.body = JSON.stringify(data);
    }
    const response = await fetch(`${API_URL}/${endpoint}`, options);
    return await response.json();
}

function getSession() {
    return {
        user_id: localStorage.getItem('user_id'),
        username: localStorage.getItem('username'),
        role: localStorage.getItem('role')
    };
}

function isAdmin() {
    return localStorage.getItem('role') === 'admin';
}

function checkAuth() {
    if (!localStorage.getItem('user_id')) {
        window.location.href = 'index.html';
        return false;
    }
    return true;
}

function showMessage(elementId, message, isError = true) {
    const el = document.getElementById(elementId);
    if (el) {
        el.textContent = message;
        el.classList.remove('hidden');
        el.classList.add(isError ? 'error' : 'success');
        setTimeout(() => el.classList.add('hidden'), 3000);
    }
}

function logout() {
    localStorage.clear();
    window.location.href = 'index.html';
}

// ============ РЕНДЕР МЕНЮ ============
function renderMenu() {
    const session = getSession();
    const menuHtml = `
        <div class="user-info">👋 ${session.username} | ${session.role === 'admin' ? 'Админ' : 'Пользователь'}</div>
        <div class="menu">
            <a href="workouts.html">📋 Тренировки</a>
            <a href="users.html">👥 Пользователи</a>
            <a href="exercises.html">🏋️ Упражнения</a>
            <a href="sets.html">📊 Подходы</a>
            <a href="profile.html">👤 Профиль</a>
            <button onclick="logout()">🚪 Выход</button>
        </div>
    `;
    document.getElementById('appHeader').innerHTML = menuHtml;
}

// ============ CRUD WORKOUTS ============
async function getWorkouts() { return await apiRequest('workouts'); }
async function getWorkout(id) { return await apiRequest(`workouts/${id}`); }
async function createWorkout(data) { return await apiRequest('workouts', 'POST', data); }
async function updateWorkout(id, data) { return await apiRequest(`workouts/${id}`, 'PUT', data); }
async function deleteWorkout(id) { return await apiRequest(`workouts/${id}`, 'DELETE'); }

// ============ CRUD USERS ============
async function getUsers() { return await apiRequest('users'); }
async function getUser(id) { return await apiRequest(`users/${id}`); }
async function createUser(data) { return await apiRequest('users', 'POST', data); }
async function updateUser(id, data) { return await apiRequest(`users/${id}`, 'PUT', data); }
async function deleteUser(id) { return await apiRequest(`users/${id}`, 'DELETE'); }

// ============ EXERCISES ============
async function getExercises() { return await apiRequest('exercises'); }

// ============ CRUD SETS ============
async function getSets(workoutId) { return await apiRequest(`sets/${workoutId}`); }
async function createSet(data) { return await apiRequest('sets', 'POST', data); }
async function updateSet(id, data) { return await apiRequest(`sets/${id}`, 'PUT', data); }
async function deleteSet(id) { return await apiRequest(`sets/${id}`, 'DELETE'); }

// ============ АВТОРИЗАЦИЯ ============
async function login(email, password) {
    try {
        const response = await fetch(`${API_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });
        const data = await response.json();
        if (data.status === 'success') {
            localStorage.setItem('user_id', data.user_id);
            localStorage.setItem('username', data.username);
            localStorage.setItem('role', data.role);
            return true;}
        return false;
    } catch(e) { return false; }
}

async function register(userData) {
    try {
        const response = await fetch(`${API_URL}/register`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(userData)
        });
        const data = await response.json();
        return data.status === 'success';
    } catch(e) { return false; }
}