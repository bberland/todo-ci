<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>To-Do List</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 2rem;
      background: #f4f4f4;
    }
    h1 {
      text-align: center;
    }
    #task-form {
      display: flex;
      gap: .5rem;
      margin-bottom: 1rem;
    }
    #task-form input {
      flex: 1;
      padding: .5rem;
    }
    #tasks {
      list-style: none;
      padding: 0;
    }
    .task {
      background: white;
      margin-bottom: .5rem;
      padding: .5rem 1rem;
      border-radius: 6px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .task.completed {
      text-decoration: line-through;
      color: gray;
    }
    button {
      margin-left: .5rem;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <h1>To-Do List</h1>

  <form id="task-form">
    <input type="text" id="new-task" placeholder="Escribe una nueva tarea..." required>
    <button type="submit">Agregar</button>
  </form>

  <ul id="tasks"></ul>

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
          li.className = `task ${task.completed ? 'completed' : ''}`;
          li.innerHTML = `
          <span contenteditable="true" data-id="${task.id}">${task.title}</span>
          <div>
              <button data-action="toggle" data-id="${task.id}">${task.completed ? 'Desmarcar' : 'Completar'}</button>
              <button data-action="delete" data-id="${task.id}">Eliminar</button>
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
        const li = e.target.closest('li');
        const completed = li.classList.contains('completed');
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
</body>
</html>