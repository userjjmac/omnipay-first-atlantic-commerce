<?php

namespace Omnipay\FirstAtlanticCommerce\Message;

//require __DIR__ . "/../vendor/omnipay/tests/src/GatewayTestCase.php";
//require __DIR__ . "/../vendor/omnipay/tests/src/TestCase.php";

use GuzzleHttp\Psr7\Response;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\FirstAtlanticCommerce\Gateway;
use Omnipay\FirstAtlanticCommerce\Message\AuthorizeRequest;
use Omnipay\FirstAtlanticCommerce\Message\AuthorizeResponse;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\Tests\TestCase;

/**
 * Class AuthorizeTest
 *
 * Test the Authorize and Purchase requests and other supporting functions for those requests.
 *
 * @package tests
 */
class AuthorizeRequestTest extends TestCase
{

    /** @var  Gateway */
    protected $gateway;

    private $purchaseOptions;

    /**
     * @var AuthorizeRequest
     */
    private $request;

    /**
     * Setup the gateway and the purchase options to be used for testing.
     */
    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'currency' => 'TTD',
                'transactionId' => '1234',
                'card' => $this->getValidCard(),
                'description' => 'Order #42',
                'metadata' => array(
                    'foo' => 'bar',
                ),
                'merchantId'=>123,
                'merchantPassword'=>'abc123',
                'acquirerId'=>'464748',
                'testMode'=>true
            )
        );
    }

    /**
     * Test a successful authorization
     */
    public function testSendSuccess()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        /** @var \Omnipay\FirstAtlanticCommerce\Message\Response $response */
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1, $response->getReasonCode());
        $this->assertEquals(307916543749, $response->getTransactionReference());
        $this->assertEquals(1234, $response->getTransactionId());
        $this->assertEquals('Transaction is approved.', $response->getMessage());
    }

    /**
     * Test get data
     */
    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame(464748, (int)$data['TransactionDetails']['AcquirerId']);
        $this->assertSame(1000, (int)$data['TransactionDetails']['Amount']);
        $this->assertSame(840, (int)$data['TransactionDetails']['Currency']);
        $this->assertSame(123, (int)$data['TransactionDetails']['MerchantId']);
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');
        
        /** @var \Omnipay\FirstAtlanticCommerce\Message\Response $response */
        $response = $this->request->send();



        $this->assertEquals(2, $response->getReasonCode());
        $this->assertEquals('Transaction is declined.', $response->getMessage());
        $this->assertEquals(307916543749, $response->getTransactionReference());;
    }

    public function testDataWithCard()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $data = $this->request->getData();

        $this->assertSame($card['number'], $data['CardDetails']['CardNumber']);
    }

    public function testDataWithToken()
    {
        $this->request->setCustomerReference('abc');
        $this->request->setToken('xyz');
        $data = $this->request->getData();

        $this->assertSame('abc', $data['customer']);
        $this->assertSame('xyz', $data['source']);
    }



    /**
     * Test the country formatting functionality
     */
    public function testFormatCountry()
    {
        //Alpha2
        $card = $this->getValidCard();
        $requestData = $this->getRequestData($card);
        $this->assertEquals(840, $requestData['BillingDetails']['BillToCountry']);

        //number
        $card['billingCountry'] = 840;
        $requestData = $this->getRequestData($card);
        $this->assertEquals(840, $requestData['BillingDetails']['BillToCountry']);

        //Alpha3
        $card['billingCountry'] = 'USA';
        $requestData = $this->getRequestData($card);
        $this->assertEquals(840, $requestData['BillingDetails']['BillToCountry']);
    }

    /**
     * Test the format state functionality with a good state
     */
    public function testFormatState()
    {
        $requestData = $this->getRequestData($this->getValidCard());
        $this->assertEquals('CA', $requestData['BillingDetails']['BillToState']);
    }

    /**
     * Test the format state functionality with a bad state
     *
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testBadState()
    {
        $card = $this->getValidCard();
        $card['billingState'] = 'California';
        $this->getRequestData($card);
    }

    /**
     * Test the format postal code functionality with a good code
     */
    public function testFormatPostCode()
    {
        $card = $this->getValidCard();
        $requestData = $this->getRequestData($card);
        $this->assertEquals('12345', $requestData['BillingDetails']['BillToZipPostCode']);

        $card['billingPostcode'] = '1 2-345';
        $requestData = $this->getRequestData($card);
        $this->assertEquals('12345', $requestData['BillingDetails']['BillToZipPostCode']);
    }


    /**
     * Test the format postal code functionality with a bad code
     *
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testBadPostCode()
    {
        $card = $this->getValidCard();
        $card['billingPostcode'] = '123#%';
        $this->getRequestData($card);
    }

    /**
     * @param $card
     *
     * @return array
     */
    private function getRequestData($card)
    {
        $purchaseOptions = $this->purchaseOptions;
        $purchaseOptions['card'] = $card;
        $request = $this->gateway->authorize($purchaseOptions);
        $requestData = $request->getData();
        return $requestData;
    }

    /**
     * Test a successful purchase. Purchases act the same as authorizations. They just have a different transaction code.
     * So the tests are basically the same.
     */
    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1, $response->getCode());
        $this->assertEquals('Transaction is approved.', $response->getMessage());
        $this->assertEquals(307916543749, $response->getTransactionReference());
        $this->assertEquals(1234, $response->getTransactionId());
    }

    /**
     * Test a failed purchase. Purchases act the same as authorizations. They just have a different transaction code.
     * So the tests are basically the same.
     */
    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');

        $response = $this->gateway->purchase($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(2, $response->getCode());
        $this->assertEquals('Transaction is declined.', $response->getMessage());
        $this->assertEquals(307916543749, $response->getTransactionReference());
    }

    public function testAuthorizeWithTokenization()
    {
        $this->setMockHttpResponse('AuthorizeToken.txt');

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotEmpty($response->getCardReference());
    }

    public function testAuthorize(){
        {

            $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
            $this->gateway->setMerchantId('88800993');
            $this->gateway->setMerchantPassword('');

            $options = [
                'amount'        => '10.00',
                'currency'      => 'TTD',
                'transactionId' => '1234',
                'card'          => $this->getValidCard(),
                'testMode'=>true

            ];


            $response = $this->gateway->authorize($options)->send();
            var_dump($response->getData());die();
        }
    }
}