# To-Do List con CodeIgniter 4

Prueba técnica Fullstack Backend con CodeIgniter 4, MySQL y Docker.  
La aplicación es un "to-do list" que permite crear, listar, editar y eliminar tareas, con frontend en HTML/JS y backend en CodeIgniter 4.

---

## 🚀 Requisitos previos
- Docker y Docker Compose instalados.
- Puerto 8080 libre para la aplicación.
- Puerto 3307 libre para MySQL (o modificarlo en docker-compose.yml).

---

## 📦 Instalación y ejecución

### 1. Clonar el repositorio
git clone https://github.com/bberland/todo-ci.git  
cd todo-ci

### 2. Construir y levantar los contenedores
docker-compose up --build -d

### 3. Ejecutar migraciones
Esto creará la tabla tasks en la base de datos:  
docker exec -it todo_app php spark migrate

---

## 🌐 Acceso a la aplicación

- Frontend: http://localhost:8080  
- API Endpoints disponibles:
  - GET /tasks → Lista todas las tareas.
  - GET /tasks/{id} → Obtiene una tarea por ID.
  - POST /tasks → Crea una nueva tarea.
  - PUT /tasks/{id} → Actualiza una tarea existente.
  - DELETE /tasks/{id} → Elimina una tarea.

Ejemplo con curl:
curl -X POST http://localhost:8080/tasks -H "Content-Type: application/json" -d '{"title":"Nueva tarea","completed":false}'

---

## 🧪 Ejecutar tests unitarios

El proyecto incluye pruebas para TaskModel y TaskController.

1. Asegúrate de que los contenedores están corriendo:
docker-compose up -d

2. Ejecutar PHPUnit dentro del contenedor:
docker exec -it todo_app ./vendor/bin/phpunit

### 📝 Nota sobre los tests
- Los tests corren migraciones automáticamente en el setUp().
- Validan la creación, consulta, actualización y eliminación de tareas.

---

## 🐳 Servicios en Docker

- app → Contenedor con Apache + PHP 8.2 + CodeIgniter.  
  Se expone en http://localhost:8080
- db → Contenedor MySQL 8.0.  
  Usuario: root  
  Password: root  
  Base de datos: todo_ci  
  Puerto expuesto: 3307 (para conexión desde el host).

---

## 🛠️ Buenas prácticas implementadas

- Backend REST en CodeIgniter 4 con ResourceController.
- Frontend en JS moderno (fetch + ES6).
- Docker Compose con servicios app y db.
- Pruebas unitarias/funcionales con PHPUnit.
- Git workflow recomendado:
  - Rama main → estable.
  - Rama develop → desarrollo.

---

## 📂 Estructura del proyecto

todo-ci/
├── app/
│   ├── Controllers/
│   │   ├── Home.php
│   │   └── TaskController.php
│   ├── Models/
│   │   └── TaskModel.php
│   ├── Views/
│   │   └── tasks_view.php
│   └── Database/
│       └── Migrations/
│           └── 2025-XX-XX-XXXX_CreateTasksTable.php
├── tests/
│   └── app/
│       ├── TaskModelTest.php
│       └── TaskControllerTest.php
├── public/
│   └── index.php
├── docker-compose.yml
├── Dockerfile
├── .env
└── README.md

---