<?php

namespace App\Tests;

use App\Models\TaskModel;
use CodeIgniter\Test\CIUnitTestCase;

class TaskModelTest extends CIUnitTestCase
{
    protected $taskModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskModel = new TaskModel();
    }

    public function testInsertTask()
    {
        $id = $this->taskModel->insert([
            'title' => 'Test Task',
            'completed' => false
        ]);

        $this->assertIsInt($id);
    }

    public function testFindTask()
    {
        $id = $this->taskModel->insert([
            'title' => 'Find Me',
            'completed' => false
        ]);

        $task = $this->taskModel->find($id);

        $this->assertEquals('Find Me', $task->title);
        $this->assertFalse((bool) $task->completed);
    }

    public function testDeleteTask()
    {
        $id = $this->taskModel->insert([
            'title' => 'Delete Me',
            'completed' => false
        ]);

        $this->taskModel->delete($id);
        $task = $this->taskModel->find($id);

        $this->assertNull($task);
    }
}