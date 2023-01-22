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

        $ordersTopLevel = $client->getOrders();

        $this->assertNotNull($ordersTopLevel);
    }

    public function testGetOrderLines()
    {
        $client = $this->createMockedClient('zalando-order-lines.json');

        $orderLinesTopLevel = $client->getOrderLines('', '');

        $this->assertNotNull($orderLinesTopLevel);
    }

    public function testGetProductOutlines()
    {
        $client = $this->createMockedClient('zalando-outlines.json');

        $outlinesResponse = $client->getProductOutlines();

        $this->assertNotNull($outlinesResponse);
    }


    private function createMockedClient(string $jsonFileName): ZalandoClient
    {
        $json = file_get_contents(__DIR__ . "/$jsonFileName");
        $mockHandler = new MockHandler([new Response(200, [], $json)]);
        return new ZalandoClient('', '', '', true, HandlerStack::create($mockHandler));
    }
}