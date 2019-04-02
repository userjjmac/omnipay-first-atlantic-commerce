<?php

namespace Omnipay\FirstAtlanticCommerce\Message;


use Omnipay\FirstAtlanticCommerce\Gateway;
use Omnipay\Tests\GatewayTestCase;

/**
 * Class StatusTest
 *
 * Test the request to get the status of a transaction.
 *
 * @package tests
 */
class StatusTest extends GatewayTestCase
{
    /** @var  Gateway */
    protected $gateway;
    /** @var  array */
    private $statusOptions;

    /**
     * @var StatusRequest
     */
    private $request;

    /**
     * Setup the gateway and status options for testing.
     */
    public function setUp()
    {
        $this->request = new StatusRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'transactionId' => '1234',
                'merchantId'=>123,
                'merchantPassword'=>'abc123',
                'acquirerId'=>'464748'
            )
        );
    }

    /**
     * Test a successful status check.
     */
    public function testSuccessfulStatus()
    {
        $this->setMockHttpResponse('StatusSuccess.txt');

        /** @var \Omnipay\FirstAtlanticCommerce\Message\Response $response */
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('Transaction is approved.', $response->getMessage());
    }

    /**
     * Test a failed status check.
     */
    public function testFailedStatus()
    {
        $this->setMockHttpResponse('StatusFailure.txt');

        /** @var \Omnipay\FirstAtlanticCommerce\Message\Response $response */
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('No Response', $response->getMessage());
    }
}