<?php

namespace App\Tests;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;

class TaskControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testGetTasksReturns200()
    {
        $result = $this->get('/tasks');
        $result->assertStatus(200);
    }

    public function testCreateTask()
    {
        $result = $this->call('post', 'tasks', [
            'title' => 'Test Task',
            'completed' => false,
        ]);
        $this->assertTrue($result->isOK());
    }
}