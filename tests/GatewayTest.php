<?php

namespace Omnipay\FirstAtlanticCommerce;

use Omnipay\FirstAtlanticCommerce\Message\AuthorizeRequest;
use Omnipay\FirstAtlanticCommerce\Message\CaptureRequest;
use Omnipay\FirstAtlanticCommerce\Message\PurchaseRequest;
use Omnipay\FirstAtlanticCommerce\Message\RefundRequest;
use Omnipay\FirstAtlanticCommerce\Message\Response;
use Omnipay\FirstAtlanticCommerce\Message\VoidRequest;
use Omnipay\Tests\GatewayTestCase;

/**
 * @property Gateway gateway
 */
class GatewayTest extends GatewayTestCase
{
    /** @var  Gateway */
    protected $gateway;

    private $purchaseOptions;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantId('123');
        $this->gateway->setMerchantPassword('abc123');

        //setup payment details
        $this->purchaseOptions = [
            'amount'        => '10.00',
            'currency'      => 'TTD',
            'transactionId' => '12313',
            'card'          => $this->getValidCard(),
            'testMode'=>true,
            'acquirerId'=>'464748',
        ];
    }


    public function testAuthorize()
    {
        /**
         * @var AuthorizeRequest $request
         */
        $request = $this->gateway->authorize($this->purchaseOptions);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\AuthorizeRequest', $request);
        $this->assertSame('10.00', $request->getAmount());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1, $response->getReasonCode());
        $this->assertEquals('Transaction is approved.', $response->getMessage());
    }

    public function testCapture()
    {
        /**
         * @var CaptureRequest $request
         */
        $request = $this->gateway->capture($this->purchaseOptions);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\CaptureRequest', $request);
        $this->assertSame('10.00', $request->getAmount());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getReasonCode());
        $this->assertEquals('Transaction successful.', $response->getMessage());
    }

    public function testPurchase()
    {
        /**
         * Single pass transaction – authorization and capture as a single transaction
         *
         * @var PurchaseRequest $request
         */
        $request = $this->gateway->purchase($this->purchaseOptions);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1, $response->getReasonCode());
        $this->assertEquals('Transaction is approved.', $response->getMessage());
    }

    public function testRefund()
    {
        /**
         * @var RefundRequest $request
         */
        $request = $this->gateway->refund($this->purchaseOptions);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\RefundRequest', $request);
        $this->assertSame('10.00', $request->getAmount());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getReasonCode());
        $this->assertEquals('Transaction successful', $response->getMessage());
    }

    public function testVoid()
    {
        /**
         * NOTE: This the reversal action
         *
         * @var VoidRequest $request
         */
        $request = $this->gateway->void($this->purchaseOptions);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\VoidRequest', $request);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getReasonCode());
        $this->assertEquals('Transaction successful', $response->getMessage());
    }

    public function testCancelRecurring()
    {
        /**
         * NOTE: This the reversal action
         *
         * @var VoidRequest $request
         */
        $request = $this->gateway->void();
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\CancelRecurringRequest', $request);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getReasonCode());
        $this->assertEquals('Transaction successful', $response->getMessage());
    }

//    public function testCreateCard()
//    {
//        /**
//         * @var CreateCardRequest $request
//         */
//        $request = $this->gateway->createCard(array('description' => 'foo'));
//
//        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\CreateCardRequest', $request);
//        $this->assertSame('foo', $request->getDescription());
//    }

//    public function testUpdateCard()
//    {
//        $request = $this->gateway->updateCard(array('description' => 'foo'));
//
//        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\UpdateCardRequest', $request);
//        $this->assertSame('cus_1MZSEtqSghKx99', $request->getCardReference());
//    }


//    public function testFetchTransaction()
//    {
//        $request = $this->gateway->fetchTransaction(array());
//
//        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\FetchTransactionRequest', $request);
//    }

//    public function testFetchBalanceTransaction()
//    {
//        $request = $this->gateway->fetchBalanceTransaction(array());
//
//        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\FetchBalanceTransactionRequest', $request);
//    }



}
