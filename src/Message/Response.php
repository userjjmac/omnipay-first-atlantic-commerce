<?php
/**
 * Created by PhpStorm.
 * User: Javon
 * Date: 4/1/19
 * Time: 4:05 PM
 */

namespace Omnipay\FirstAtlanticCommerce\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{
    /**
     * Request id
     *
     * @var string URL
     */
    protected $requestId = null;

    /**
     * @var array
     */
    protected $headers = [];

    public function __construct(RequestInterface $request, $data, $headers = [])
    {
        $this->request = $request;
        $xml = simplexml_load_string($data);
        $json = json_encode($xml);
        $this->data = json_decode($json,TRUE);
        $this->headers = $headers;
    }

    /**
     * Is the transaction successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        if ( isset($this->data['CreditCardTransactionResults']['ResponseCode']) && $this->data['CreditCardTransactionResults']['ResponseCode'] == 1){
            return true;
        }
        elseif (isset($this->data['ResponseCode']) && $this->data['ResponseCode'] == 1){
            return true;
        }
        elseif (isset($this->data['ResponseCode']) && $this->data['ResponseCode'] == 0){
            return true;
        }
        return false;
    }

    /**
     * @return null
     */
    public function getTransactionReference()
    {
        return isset($this->data['CreditCardTransactionResults']['ReferenceNumber']) ? $this->data['CreditCardTransactionResults']['ReferenceNumber'] : null;
    }

    /**
     * @return null
     */
    public function getTransactionId()
    {
        return isset($this->data['OrderNumber']) ? $this->data['OrderNumber'] : null;
    }

    /**
     * @return null
     */
    public function getReasonCode()
    {
        if (isset($this->data['CreditCardTransactionResults']['ReasonCode'])){
            return $this->data['CreditCardTransactionResults']['ReasonCode'];
        }
        elseif (isset($this->data['ReasonCode'])){
            return $this->data['ReasonCode'];
        }
        return null;
    }

    public function getMessage()
    {
        if (isset($this->data['CreditCardTransactionResults']['ReasonCodeDescription'])){
            return $this->data['CreditCardTransactionResults']['ReasonCodeDescription'];
        }
        elseif (isset($this->data['ReasonCodeDescription'])){
            return $this->data['ReasonCodeDescription'];
        }
        elseif (isset($this->data['ResponseCodeDescription'])){
            return $this->data['ResponseCodeDescription'];
        }
        return null;
    }

    /**
     * Return card reference
     *
     * @return string
     */
    public function getCardReference()
    {
        return ( 
                array_keys_exists('CreditCardTransactionResults', $this->data) 
                && array_keys_exists('TokenizedPAN', $this->data['CreditCardTransactionResults'])
               )? $this->data['CreditCardTransactionResults']['TokenizedPAN'] : 
                ( array_key_exists('TokenizedPAN', $this->data) ? $this->data['TokenizedPAN'] : 
                    ( array_key_exists('Token', $this->data) ? $this->data['Token'] : 
                        null 
                    )
                );
    }


}
