<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>To-Do List</title>
  <style>
    .task.completed {
      text-decoration: line-through;
      color: gray;
    }
  </style>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container py-5">
    <h1 class="mb-4 text-center">To-Do List</h1>

    <form id="task-form" class="d-flex mb-4">
      <input type="text" id="new-task" class="form-control me-2" placeholder="Escribe una nueva tarea..." required>
      <button type="submit" class="btn btn-primary">Agregar</button>
    </form>

    <ul id="tasks" class="list-group"></ul>
  </div>

  <script type="module">
    const API_URL = '/tasks';

    const taskList = document.querySelector('#tasks');
    const taskForm = document.querySelector('#task-form');
    const newTaskInput = document.querySelector('#new-task');

    // Fetch helper
    const request = async (url, options = {}) => {
      const res = await fetch(url, {
        headers: { 'Content-Type': 'application/json' },
        ...options,
      });
      return res.json();
    };

    // Render tasks
    const renderTasks = async () => {
      const data = await request(API_URL);
      const tasks = Array.isArray(data) ? data : data.data || [];
      taskList.innerHTML = '';
      tasks.forEach(task => {
          const li = document.createElement('li');
          li.className = `list-group-item d-flex justify-content-between align-items-center`;
          li.innerHTML = `
            <span class="task ${task.completed ? 'completed' : ''}">${task.title}</span>
            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-${task.completed ? 'warning' : 'success'}" data-action="toggle" data-id="${task.id}">
                ${task.completed ? 'Desmarcar' : 'Completar'}
              </button>
              <button class="btn btn-sm btn-danger" data-action="delete" data-id="${task.id}">Eliminar</button>
            </div>
          `;
          taskList.appendChild(li);
      });
    };


    // Add task
    taskForm.addEventListener('submit', async e => {
      e.preventDefault();
      const title = newTaskInput.value.trim();
      if (!title) return;
      await request(API_URL, {
        method: 'POST',
        body: JSON.stringify({ title }),
      });
      newTaskInput.value = '';
      renderTasks();
    });

    // Delegación de eventos para editar, completar y eliminar
    taskList.addEventListener('click', async e => {
      const { action, id } = e.target.dataset;
      if (!action) return;

      if (action === 'delete') {
        await request(`${API_URL}/${id}`, { method: 'DELETE' });
      }

      if (action === 'toggle') {
        const span = e.target.closest('li').querySelector('span');
        const completed = span.classList.contains('completed');
        await request(`${API_URL}/${id}`, {
          method: 'PUT',
          body: JSON.stringify({ completed: !completed }),
        });
      }

      renderTasks();
    });

    // Editar título (al perder foco)
    taskList.addEventListener('blur', async e => {
      if (e.target.matches('[contenteditable]')) {
        const id = e.target.dataset.id;
        const title = e.target.textContent.trim();
        if (title) {
          await request(`${API_URL}/${id}`, {
            method: 'PUT',
            body: JSON.stringify({ title }),
          });
          renderTasks();
        }
      }
    }, true);

    // Inicial
    renderTasks();
  </script>
  <!-- Bootstrap JS Bundle CDN -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>