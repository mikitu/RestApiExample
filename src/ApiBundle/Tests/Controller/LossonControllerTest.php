<?php

namespace ApiBundle\Tests\Controller;

use ApiBundle\Tests\Fixtures\Entity\LoadLessonsData;
use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use ApiBundle\Tests\Fixtures\Entity\LoadLessonData;

class LossonControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->auth = array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'userpass',
        );
        $this->client = static::createClient(array(), $this->auth);
        $fixtures = array('ApiBundle\Tests\Fixtures\Entity\LoadLessonsData');
        $this->loadFixtures($fixtures);
    }

    public function testJsonGetLessonsAction()
    {
        $route =  $this->getUrl('api_get_lessons');

        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));
    }

    public function testJsonGetLessonAction()
    {
        $lessons = LoadLessonData::$lessons;
        $lesson = array_pop($lessons);
        $route =  $this->getUrl('api_get_lesson', array('id' => $lesson->getId(), '_format' => 'json'));
        $this->client->request('GET', $route, array('ACCEPT' => 'application/json'));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));
    }

    public function testJsonPostLessonAction()
    {
        $this->client = static::createClient();
        $this->client->request(
            'POST',
            '/api/lessons',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"title1","body":"body1"}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }
    public function testHeadRoute()
    {
        $lessons = LoadLessonData::$lessons;
        $lesson = array_pop($lessons);
        $this->client->request(
            'HEAD',
            sprintf('/api/lessons/%d', $lesson->getId()),
            array('ACCEPT' => 'application/json')
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200, false);
    }
    public function testJsonNewLessonAction()
    {
        $this->client->request(
            'GET',
            '/api/lessons/new',
            array(),
            array()
        );
        $this->assertJsonResponse($this->client->getResponse(), 200, true);
        $this->assertEquals(
            '{"children":{"title":[],"body":[]}}',
            $this->client->getResponse()->getContent(),
            $this->client->getResponse()->getContent()
        );
    }

    public function testJsonPostLessonActionShouldReturn400WithBadParameters()
    {
        $this->client->request(
            'POST',
            '/api/lessons',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"titles":"title1","bodys":"body1"}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 400, false);
    }
    public function testJsonPutLessonActionShouldModify()
    {
        $lessons = LoadLessonData::$lessons;
        $lesson = array_pop($lessons);
        $this->client->request(
            'GET',
            sprintf('/api/lessons/%d',
                $lesson->getId()), array('ACCEPT' => 'application/json')
        );
        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $this->client->request(
            'PUT',
            sprintf('/api/lessons/%d', $lesson->getId()),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"abc","body":"def"}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 204, false);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Location',
                sprintf('http://localhost/api/lessons/%d', $lesson->getId())
            ),
            $this->client->getResponse()->headers
        );
    }
    public function testJsonPutLessonActionShouldCreate()
    {
        $id = 0;
        $this->client->request('GET', sprintf('/api/lessons/%d', $id), array('ACCEPT' => 'application/json'));
        $this->assertEquals(
            404,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $this->client->request(
            'PUT',
            sprintf('/api/lessons/%d', $id),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"title":"abc","body":"def"}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }
    public function testJsonPatchLessonAction()
    {
        $lessons = LoadLessonData::$lessons;
        $lesson = array_pop($lessons);
        $this->client->request(
            'PATCH',
            sprintf('/api/lessons/%d', $lesson->getId()),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"body":"def"}'
        );
        $this->assertJsonResponse($this->client->getResponse(), 204, false);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Location',
                sprintf('http://localhost/api/lessons/%d', $lesson->getId())
            ),
            $this->client->getResponse()->headers
        );
    }
    protected function assertJsonResponse(
        $response,
        $statusCode = 200,
        $checkValidJson =  true,
        $contentType = 'application/json'
    )
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );
        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }
}
