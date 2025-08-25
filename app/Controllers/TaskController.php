<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class TaskController extends ResourceController
{
    protected $modelName = TaskModel::class;
    protected $format    = 'json';

    // GET /tasks
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // GET /tasks/{id}
    public function show(int $id = null)
    {
        $task = $this->model->find($id);
        if (!$task) {
            return $this->failNotFound("Task with ID $id not found");
        }
        return $this->respond($task);
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

        return $this->respondCreated(['id' => $id, 'message' => 'Task created successfully']);
    }

    // PUT /tasks/{id}
    public function update(int $id = null)
    {
        $data = $this->request->getJSON(true);

        $task = $this->model->find($id);
        if (!$task) {
            return $this->failNotFound("Task with ID $id not found");
        }

        if (!$this->validateTask($data, 'update')) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $updated = $this->model->update($id, [
            'title'     => $data['title']     ?? $task['title'],
            'completed' => $data['completed'] ?? $task['completed'],
        ]);

        if ($updated === false) {
            return $this->failServerError('Failed to update task');
        }

        return $this->respond(['message' => 'Task updated successfully']);
    }

    // DELETE /tasks/{id}
    public function delete(int $id = null)
    {
        $task = $this->model->find($id);
        if (!$task) {
            return $this->failNotFound("Task with ID $id not found");
        }

        if (!$this->model->delete($id)) {
            return $this->failServerError('Failed to delete task');
        }
        return $this->respondDeleted(['message' => 'Task deleted successfully']);
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
