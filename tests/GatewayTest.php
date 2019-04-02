<?php

namespace Omnipay\FirstAtlanticCommerce;

use Omnipay\Tests\GatewayTestCase;

/**
 * @property Gateway gateway
 */
class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }


    public function testAuthorize()
    {
        $request = $this->gateway->authorize(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\AuthorizeRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testCapture()
    {
        $request = $this->gateway->capture(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\CaptureRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testRefund()
    {
        $request = $this->gateway->refund(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\RefundRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testVoid()
    {
        $request = $this->gateway->void();

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\VoidRequest', $request);
    }

    public function testCreateCard()
    {
        $request = $this->gateway->createCard(array('description' => 'foo'));

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\CreateCardRequest', $request);
        $this->assertSame('foo', $request->getDescription());
    }

    public function testUpdateCard()
    {
        $request = $this->gateway->updateCard(array('cardReference' => 'cus_1MZSEtqSghKx99'));

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\UpdateCardRequest', $request);
        $this->assertSame('cus_1MZSEtqSghKx99', $request->getCardReference());
    }

    //TODO- test for status,
    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(array());

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\FetchTransactionRequest', $request);
    }

    public function testFetchBalanceTransaction()
    {
        $request = $this->gateway->fetchBalanceTransaction(array());

        $this->assertInstanceOf('Omnipay\FirstAtlanticCommerce\Message\FetchBalanceTransactionRequest', $request);
    }



}
