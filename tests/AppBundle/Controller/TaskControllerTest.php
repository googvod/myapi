<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TaskControllerTest
 * @package Tests\AppBundle\Controller
 */
class TaskControllerTest extends WebTestCase
{
    /**
     * @var string
     */
    private $token;

    /**
     * test getAccessToken Action
     */
    protected function setUp()
    {
        $client = static::createClient();

        $client->request('POST', '/api/get-access-token', [
            'name'=> 'test',
            'password' => 'admin'
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $data = \GuzzleHttp\json_decode($client->getResponse()->getContent());

        $this->assertTrue(!empty($data) && isset($data->token));

        $this->token = $data->token;
    }

    /**
     * test tasks Actions
     */
    public function testTask()
    {
        $client = static::createClient();

        $client->setServerParameters(['HTTP_AUTHORIZATION' => 'Bearer ' . $this->token]);
        $client->request('POST', '/api/task/create', ['content'=> 'test content', 'completed' => '0']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = \GuzzleHttp\json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue(!empty($data['item']));

        $this->updateTask($client, $data['item']['id']);
        $this->deleteTask($client, $data['item']['id']);
        $this->getTask($client, $data['item']['id']);
    }

    /**
     * @param $client
     * @param int $id
     */
    private function updateTask($client, $id)
    {
        $client->request('PUT', '/api/task/' . $id, [
            'content'=> 'test content 2',
            'completed' => '0'
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $dataEdited = \GuzzleHttp\json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue(!empty($dataEdited['item']));
        $this->assertTrue(!empty($dataEdited['item']['content'] == 'test content 2'));
    }

    /**
     * @param $client
     * @param int $id
     */
    private function deleteTask($client, $id)
    {
        $client->request('DELETE', '/api/task/' . $id);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @param $client
     * @param int $id
     */
    private function getTask($client, $id)
    {
        $client->request('GET', '/api/task/' . $id);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}
