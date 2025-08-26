<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\TaskModel;

class TaskController extends ResourceController
{
    protected $modelName = TaskModel::class;
    protected $format    = 'json';

    // GET /tasks
    public function index()
    {
        $tasks = $this->model->findAll();
        
        // Convierte cada entidad a array
        $tasksArray = array_map(fn($task) => $task->toArray(), $tasks);
        
        return $this->respond($tasksArray);
    }

    // GET /tasks/{id}
    public function show($id = null)
    {
        $task = $this->model->find($id);
        if (!$task) {
            return $this->failNotFound("Task with ID $id not found");
        }

        return $this->respond($task->toArray());
    }

    // POST /tasks
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$this->validateTask($data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $id = $this->model->insert([
            'title'     => $data['title'],
            'completed' => $data['completed'] ?? false,
        ], true);

        if ($id === false) {
            return $this->failServerError('Failed to create task');
        }

        $task = $this->model->find($id);
        return $this->respondCreated(['task' => $task->toArray(), 'message' => 'Task created successfully']);
    }

    // PUT /tasks/{id}
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);

        $task = $this->model->find($id);
        if (!$task) {
            return $this->failNotFound("Task with ID $id not found");
        }

        $updated = $this->model->update($id, [
            'title'     => array_key_exists('title', $data) ? $data['title'] : $task->title,
            'completed' => array_key_exists('completed', $data) ? (bool)$data['completed'] : $task->completed,
        ]);

        if ($updated === false) {
            return $this->failServerError('Failed to update task');
        }

        $task = $this->model->find($id);
        return $this->respond(['task' => $task->toArray(), 'message' => 'Task updated successfully']);
    }

    // DELETE /tasks/{id}
    public function delete($id = null)
    {
        $task = $this->model->find($id);
        if (!$task) {
            return $this->failNotFound("Task with ID $id not found");
        }

        if (!$this->model->delete($id)) {
            return $this->failServerError('Failed to delete task');
        }
        return $this->respondDeleted(['task' => $task->toArray(), 'message' => 'Task deleted successfully']);
    }

    // Validation method
    private function validateTask(array $data, string $context = 'create'): bool
    {
        $rules = [
            'title' => 'required|min_length[3]',
        ];
        return $this->validate($rules);
    }
}
