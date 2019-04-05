<?php

namespace Omnipay\FirstAtlanticCommerce\Message;


use Omnipay\FirstAtlanticCommerce\Gateway;
use Omnipay\Tests\GatewayTestCase;

/**
 * Class TransactionModificationTest
 *
 * Tests of the transaction modification procedures in FAC.
 *
 * @package tests
 */
class TransactionModificationTest extends GatewayTestCase
{
    /** @var  Gateway */
    protected $gateway;
    /** @var  array */
    private $options;

    /**
     * @var AbstractRequest
     */
    private $request;

    /**
     * Setup the gateway and options for testing.
     */
    public function setUp()
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantId('123');
        $this->gateway->setMerchantPassword('abc1233');

        $this->options = [
            'amount' => '10.00',
            'currency' => 'USD',
            'transactionId' => '1234'
        ];

        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'currency' => 'USD',
                'transactionId' => '1234',
                'card' => $this->getValidCard(),
                'description' => 'Order #42',
                'metadata' => array(
                    'foo' => 'bar',
                ),
                'merchantId'=>123,
                'merchantPassword'=>'abc123',
                'acquirerId'=>'464748'
            )
        );


    }
}