<?php


namespace Omnipay\FirstAtlanticCommerce\Message;

use Omnipay\FirstAtlanticCommerce\Gateway;
use Omnipay\Tests\TestCase;

class Authorize3DSRequestTest extends TestCase
{
    /** @var  Gateway */
    protected $gateway;

    private $purchaseOptions;

    /**
     * @var Authorize3DSRequest
     */
    private $request;

    /**
     * Setup the request, gateway and the purchase options to be used for testing.
     */
    public function setUp()
    {
        //set up AuthorizeRequest
        $this->request = new Authorize3DSRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'currency' => 'TTD',
                'transactionId' => '12315',
                'card' => $this->getValidCard(),
                'description' => 'Order #43',
                'metadata' => array(
                    'foo' => 'bar',
                ),
                'merchantId'=>123,
                'merchantPassword'=>'abc123',
                'acquirerId'=>'464748',
                'eciIndicatorValue'=>'06',
                'cavvValue'=>'MjAxOS0wNC0wMyAxMzoyMjozMy43OTA5NjI0MyArMDAwMCBVVEMgbT0rNDEyNTA4NC40Mjk5MDYxNzE',
                'transactionStain'=>'123',
                'authenticationResult'=>'Y',
                'testMode'=>true
            )
        );

        //set up gateway
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantId('123');
        $this->gateway->setMerchantPassword('abc123');

        //setup payment details
        $this->purchaseOptions = [
            'amount'        => '10.00',
            'currency'      => 'TTD',
            'transactionId' => '1237',
            'card'          => $this->getValidCard(),
            'testMode'=>true
        ];
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
        $this->assertSame(780, (int)$data['TransactionDetails']['Currency']);
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

}
