<?php

namespace Omnipay\FirstAtlanticCommerce;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Http\Client;
use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * First Atlantic Commerce Payment Gateway 2 (XML POST Service)
 *
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 */
class Gateway extends AbstractGateway
{
    use ParameterTrait;

    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        parent::__construct(new Client(), $httpRequest);
    }

    /**
     * @return string Gateway name.
     */
    public function getName()
    {
        return 'First Atlantic Commerce Payment Gateway 2';
    }

    /**
     * @return array Default parameters.
     */
    public function getDefaultParameters()
    {
        return [
            'merchantId'       => null,
            'merchantPassword' => null,
            'acquirerId'       => '464748',
            'testMode'         => false,
            'requireAvsCheck'  => true
        ];
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function setMerchantPassword($value)
    {
        return $this->setParameter('merchantPassword', $value);
    }

    public function setAcquirerId($value)
    {
        return $this->setAcquirerId($value);
    }

    /**
     * Authorize Request.
     *
     * Authorize an amount on the customer’s card.
     *
     * An Authorize request is similar to a purchase request but the
     * charge issues an authorization (or pre-authorization), and no money
     * is transferred.  The transaction will need to be captured later
     * in order to effect payment. Uncaptured charges expire in 7 days.
     *
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\AuthorizeRequest
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\AuthorizeRequest', $parameters);
    }

    /**
     * Capture Request.
     *
     * Capture an amount you have previously authorized.
     *
     * Use this request to capture and process a previously created authorization.
     *
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\CaptureRequest
     */
    public function capture(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\CaptureRequest', $parameters);
    }

    /**
     *  Purchase request.
     *
     *  To charge a credit card, you create a new charge object.
     *  Authorize and immediately capture an amount on the customer’s card.
     *
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\PurchaseRequest', $parameters);
    }

    /**
     *  Refund Request.
     *
     * When you create a new refund, you must specify a
     * charge to create it on.
     *
     * Creating a new refund will refund a charge that has
     * previously been created but not yet refunded. Funds will
     * be refunded to the credit or debit card that was originally
     * charged. The fees you were originally charged are also
     * refunded.
     *
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\RefundRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\RefundRequest', $parameters);
    }

    /**
     * Fetch Transaction Request.
     *  Reverse an already submitted transaction that hasn't been settled.
     *
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\VoidRequest
     */
    public function void(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\VoidRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\FetchTransactionRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\FirstAtlanticCommerce\Message\FetchBalanceTransactionRequest
     */
    public function fetchBalanceTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\FetchBalanceTransactionRequest', $parameters);
    }

    /**
     *  Retrieve the status of any previous transaction.
     *
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\StatusRequest
     */
    public function status(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\StatusRequest', $parameters);
    }

    /**
     *
     *  Create Card.
     *  Create a stored card and return the reference token for future transactions.
     *
     * This call can be used to create a new customer or add a card
     * to an existing customer.  If a customerReference is passed in then
     * a card is added to an existing customer.
     *
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\CreateCardRequest
     */
    public function createCard(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\CreateCardRequest', $parameters);
    }

    /**
     *  Update a stored card.
     *
     * If you need to update only some card details, like the billing
     * address or expiration date, you can do so without having to re-enter
     * the full card details.
     *
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\UpdateCardRequest
     */
    public function updateCard(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\UpdateCardRequest', $parameters);
    }

    /**
     * Delete a card.
     *
     * Yu can delete cards from a customer or recipient. If you delete a
     * card that is currently the default card on a customer or recipient,
     * the most recently added card will be used as the new default. If you
     * delete the last remaining card on a customer or recipient, the
     * default_card attribute on the card's owner will become null.
     *
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\DeleteCardRequest
     */
    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\FirstAtlanticCommerce\Message\DeleteCardRequest', $parameters);
    }


}
