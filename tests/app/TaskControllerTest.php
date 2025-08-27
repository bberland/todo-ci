<?php

namespace App\Tests;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;

class TaskControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        // Correr migraciones en cada test
        $migrate = \Config\Services::migrations();
        $migrate->setNamespace(null);
        $migrate->latest();
    }

    public function testGetTasksReturns200()
    {
        $result = $this->get('/tasks');
        $result->assertStatus(200);
    }

    public function testCreateTask()
    {
        $payload = [
            'title' => 'Test Task',
            'completed' => false,
        ];

        $result = $this->withBody(json_encode($payload))
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->call('post', 'tasks');

        $this->assertTrue($result->isOK());
    }
}