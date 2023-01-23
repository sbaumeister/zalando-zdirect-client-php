<?php

namespace Baumeister\ZalandoClient\Tests;

use Baumeister\ZalandoClient\ZalandoClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ZalandoClientTest extends TestCase
{
    public function testGetOrders()
    {
        $client = $this->createMockedClient('zalando-orders.json');

        $response = $client->getOrders();

        $this->assertNotNull($response);
    }

    public function testGetOrderLines()
    {
        $client = $this->createMockedClient('zalando-order-lines.json');

        $response = $client->getOrderLines('', '');

        $this->assertNotNull($response);
    }

    public function testGetProductOutlines()
    {
        $client = $this->createMockedClient('zalando-outlines.json');

        $response = $client->getProductOutlines();

        $this->assertNotNull($response);
    }

    public function testGetAttributeType()
    {
        $client = $this->createMockedClient('zalando-attribute-type.json');

        $response = $client->getAttributeType('color_code');

        $this->assertNotNull($response);
    }

    public function testGetAttributeValues()
    {
        $client = $this->createMockedClient('zalando-attribute-values.json');

        $response = $client->getAttributeValues('target_genders');

        var_dump($response);
        $this->assertNotNull($response);
    }


    private function createMockedClient(string $jsonFileName): ZalandoClient
    {
        $json = file_get_contents(__DIR__ . "/$jsonFileName");
        $mockHandler = new MockHandler([new Response(200, [], $json)]);
        return new ZalandoClient('', '', '', true, HandlerStack::create($mockHandler));
    }
}