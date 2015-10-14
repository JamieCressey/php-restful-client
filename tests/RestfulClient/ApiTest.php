<?php

use RestfulClient\ApiClient;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    private $api;

    public function setUp()
    {
        $this->api = new ApiClient('#your_api_key', '#your_api_secret', 'http://restful.jamiecressey.com:5000');
    }

    public function testCreateBook()
    {
        $title = strval(time());
	$resp = $this->api->post('/books/', array(
	    'title' => $title,
	    'author' => 'testing'
	));
	$this->assertEquals($resp->ok(), True);
	$this->assertEmpty((array) $resp->errors());
	return $title;
    }

    /**
     * @depends testCreateBook
     */
    public function testGetBook($title)
    {
	$resp = $this->api->get('/book/'.$title.'/');
	$this->assertEquals($resp->ok(), True);
	$this->assertEmpty((array) $resp->errors());
    }

    /**
     * @depends testGetBook
     */
    public function testGetBooks()
    {
	$resp = $this->api->get('/books/');
	$this->assertEquals($resp->ok(), True);
	$this->assertEmpty((array) $resp->errors());
    }

    /**
     * @depends testCreateBook
     */
    public function testUpdateBook($title)
    {
	$resp = $this->api->put('/book/'.$title.'/', array(
            'release_date' => '01/01/2001'
        ));
	$this->assertEquals($resp->ok(), True);
	$this->assertEmpty((array) $resp->errors());
    }

    /**
     * @depends testCreateBook
     */
    public function testDeleteBook($title)
    {
	$resp = $this->api->delete('/book/'.$title.'/');
	$this->assertEquals($resp->ok(), True);
	$this->assertEmpty((array) $resp->errors());
    }
}
