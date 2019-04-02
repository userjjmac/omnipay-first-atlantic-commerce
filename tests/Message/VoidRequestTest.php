<?php

namespace Omnipay\FirstAtlanticCommerce\Message;

use Omnipay\Tests\TestCase;

class VoidRequestTest extends TestCase
{
    /**
     * @var CaptureRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'currency' => 'USD',
                'transactionId' => '1234',
                'merchantId'=>123,
                'merchantPassword'=>'abc123',
                'acquirerId'=>'464748',
                'testMode'=>true
            )
        );
    }

    public function testEndpoint()
    {
        //test mode set to true
        $this->assertSame('https://ecm.firstatlanticcommerce.com/PGServiceXML/TransactionModification', $this->request->getEndpoint());
    }

    /**
     * Test a successful Void.
     */
    public function testSuccessfulVoid()
    {
        $this->setMockHttpResponse('ModificationSuccess.txt');

        /** @var \Omnipay\FirstAtlanticCommerce\Message\Response $response */
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getReasonCode());
        $this->assertEquals('Success', $response->getMessage());
    }

    /**
     * Test a failed Void.
     */
    public function testFailedVoid()
    {
        $this->setMockHttpResponse('ModificationFailed.txt');

        /** @var \Omnipay\FirstAtlanticCommerce\Message\Response $response */
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(1100, $response->getReasonCode());
        $this->assertEquals('Failed', $response->getMessage());
    }

    
}
