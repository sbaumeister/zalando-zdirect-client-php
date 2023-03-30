<?php

namespace Baumeister\ZalandoClient;

use Baumeister\ZalandoClient\Model\AttributesPagedResponse;
use Baumeister\ZalandoClient\Model\LookUp;
use Baumeister\ZalandoClient\Model\OrderItem;
use Baumeister\ZalandoClient\Model\OrderLine;
use Baumeister\ZalandoClient\Model\OrderLinesTopLevel;
use Baumeister\ZalandoClient\Model\OrdersTopLevel;
use Baumeister\ZalandoClient\Model\OutlinesPagedResponse;
use Baumeister\ZalandoClient\Model\ProductPrice;
use Baumeister\ZalandoClient\Model\ProductSubmission;
use Baumeister\ZalandoClient\Model\ResourceObject;
use Baumeister\ZalandoClient\Model\StockUpdatePerSalesChannel;
use Baumeister\ZalandoClient\Model\StockUpdatesRequest;
use Baumeister\ZalandoClient\Model\TypeResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;
use JsonMapper;
use JsonMapper_Exception;

class ZalandoClient
{
    private Client $guzzleClient;
    private string $accessToken = '';
    private string $clientId;
    private string $clientSecret;
    private JsonMapper $jsonMapper;
    private string $merchantId;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $merchantId,
        bool $useSandbox = false,
        $handlerStack = null
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->merchantId = $merchantId;
        $this->jsonMapper = new JsonMapper();
        $typeToClassMap = [
            'OrderItem' => OrderItem::class,
            'OrderLine' => OrderLine::class,
        ];
        $this->jsonMapper->classMap[ResourceObject::class] = function ($class, $object) use ($typeToClassMap) {
            return $typeToClassMap[$object->type];
        };
        $url = $useSandbox ? 'https://api-sandbox.merchants.zalando.com' : 'https://api.merchants.zalando.com';
        $stack = $handlerStack ?: HandlerStack::create();
        $stack->push(
            GuzzleRetryMiddleware::factory([
                'retry_enabled' => true
            ])
        );
        $this->guzzleClient = new Client([
            'base_uri' => $url,
            'handler' => $stack,
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function authenticate(): void
    {
        $response = $this->guzzleClient->post('/auth/token', [
            'auth' => [$this->clientId, $this->clientSecret],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);
        $data = json_decode($response->getBody()->getContents());
        $this->accessToken = $data->access_token;
    }

    /**
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function getOrders(
        int $pageNumber = 0,
        int $pageSize = 50,
        bool $exported = false,
        string $orderStatus = 'approved'
    ): OrdersTopLevel {
        $response = $this->guzzleClient->get("/merchants/$this->merchantId/orders", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Accept' => 'application/vnd.api+json'
            ],
            'query' => [
                'exported' => $exported ? 'true' : 'false',
                'include' => 'order_items,order_lines',
                'order_status' => $orderStatus,
                'page[size]' => $pageSize,
                'page[number]' => $pageNumber
            ]
        ]);
        $data = json_decode($response->getBody()->getContents());
        return $this->jsonMapper->map($data, new OrdersTopLevel());
    }

    /**
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function getProductEans(string $ean): LookUp
    {
        $response = $this->guzzleClient->get("/products/identifiers/$ean", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
            ]
        ]);
        $data = json_decode($response->getBody()->getContents());
        return $this->jsonMapper->map($data, new LookUp());
    }

    /**
     * @throws GuzzleException
     */
    public function putProductIdentifier(string $ean, string $productSku): bool
    {
        // TODO: replace with real object
        $body = json_encode(['merchant_product_simple_id' => $productSku], JSON_FORCE_OBJECT);
        $response = $this->guzzleClient->put("/merchants/$this->merchantId/products/identifiers/$ean", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Content-Type' => 'application/json'
            ],
            'body' => $body
        ]);
        if ($response->getStatusCode() == 204) {
            return true;
        }
        return false;
    }

    public function postProductSubmission(ProductSubmission $productSubmission): bool
    {
        $body = json_encode($productSubmission);
        $response = $this->guzzleClient->post("/merchants/$this->merchantId/product-submissions", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Content-Type' => 'application/json'
            ],
            'body' => $body
        ]);
        if ($response->getStatusCode() == 200) {
            return true;
        }
        return false;
    }

    /**
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function getOrderLines(string $orderId, string $orderItemId): OrderLinesTopLevel {
        $response = $this->guzzleClient->get("/merchants/$this->merchantId/orders/$orderId/items/$orderItemId/lines", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Accept' => 'application/vnd.api+json'
            ],
            'query' => array_filter([
                'include' => ''
            ])
        ]);
        $data = json_decode($response->getBody()->getContents());
        return $this->jsonMapper->map($data, new OrderLinesTopLevel());
    }

    /**
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function getProductOutlines(): OutlinesPagedResponse {
        $response = $this->guzzleClient->get("/merchants/$this->merchantId/outlines", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Accept' => 'application/vnd.api+json'
            ],
        ]);
        $data = json_decode($response->getBody()->getContents());
        return $this->jsonMapper->map($data, new OutlinesPagedResponse());
    }

    /**
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function getAttributeType(string $type): TypeResponse {
        $response = $this->guzzleClient->get("/merchants/$this->merchantId/attribute-types/$type", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Accept' => 'application/vnd.api+json'
            ],
        ]);
        $data = json_decode($response->getBody()->getContents());
        return $this->jsonMapper->map($data, new TypeResponse());
    }

    /**
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function getAttributeValues(string $type): AttributesPagedResponse {
        $response = $this->guzzleClient->get("/merchants/$this->merchantId/attribute-types/$type/attributes", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Accept' => 'application/vnd.api+json'
            ],
        ]);
        $data = json_decode($response->getBody()->getContents());
        return $this->jsonMapper->map($data, new AttributesPagedResponse());
    }

    /**
     * @throws GuzzleException
     */
    public function patchOrderSetExported(string $orderId, string $merchantOrderId): bool
    {
        $body = json_encode([
            'data' => [
                'id' => $orderId,
                'type' => 'Order',
                'attributes' => [
                    'merchant_order_id' => $merchantOrderId
                ]
            ]
        ], JSON_FORCE_OBJECT);
        return $this->patchOrder($orderId, $body);
    }

    /**
     * @throws GuzzleException
     */
    public function patchOrderLineSetStatus(string $orderId, string $orderItemId, string $orderLineId, string $status): bool
    {
        $body = json_encode([
            'data' => [
                'id' => $orderLineId,
                'type' => 'OrderLine',
                'attributes' => [
                    'status' => $status
                ]
            ]
        ], JSON_FORCE_OBJECT);
        $response = $this->guzzleClient->patch("/merchants/$this->merchantId/orders/$orderId/items/$orderItemId/lines/$orderLineId", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Content-Type' => 'application/vnd.api+json'
            ],
            'body' => $body
        ]);
        if ($response->getStatusCode() == 204) {
            return true;
        }
        return false;
    }

    /**
     * @throws GuzzleException
     */
    public function patchOrderSetTrackingNumbers(string $orderId, string $trackingNumber, ?string $returnTrackingNumber = null): bool
    {
        $body = json_encode([
            'data' => [
                'id' => $orderId,
                'type' => 'Order',
                'attributes' => array_filter([
                    'tracking_number' => $trackingNumber,
                    'return_tracking_number' => $returnTrackingNumber,
                ])
            ]
        ], JSON_FORCE_OBJECT);
        return $this->patchOrder($orderId, $body);
    }

    /**
     * @param StockUpdatePerSalesChannel[] $stocks
     * @throws GuzzleException
     */
    public function postStocks(array $stocks): mixed
    {
        $stockUpdatesRequest = new StockUpdatesRequest();
        $stockUpdatesRequest->items = $stocks;
        $body = json_encode($stockUpdatesRequest);
        $response = $this->guzzleClient->post("/merchants/$this->merchantId/stocks", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Content-Type' => 'application/json'
            ],
            'body' => $body
        ]);
        if ($response->getStatusCode() == 207) {
            return json_decode($response->getBody()->getContents());
        }
        return false;
    }

    /**
     * @param ProductPrice[] $prices
     * @throws GuzzleException
     */
    public function postPrices(array $prices): mixed
    {
        // TODO: replace with real object
        $body = json_encode(['product_prices' => $prices], JSON_FORCE_OBJECT);
        $response = $this->guzzleClient->post("/merchants/$this->merchantId/stocks", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Content-Type' => 'application/json'
            ],
            'body' => $body
        ]);
        if ($response->getStatusCode() == 207) {
            return json_decode($response->getBody()->getContents());
        }
        return false;
    }

    /**
     * @throws GuzzleException
     */
    private function patchOrder(string $orderId, $body): bool
    {
        $response = $this->guzzleClient->patch("/merchants/$this->merchantId/orders/$orderId", [
            'headers' => [
                'Authorization' => "Bearer $this->accessToken",
                'Content-Type' => 'application/vnd.api+json'
            ],
            'body' => $body
        ]);
        if ($response->getStatusCode() == 204) {
            return true;
        }
        return false;
    }
}