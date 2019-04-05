<?php

namespace Omnipay\FirstAtlanticCommerce\Message;


/**
 * FACPG2 Refund Request
 *
 * Required Parameters:
 * transactionId - Corresponds to the merchant's transaction ID
 * amount - eg. "10.00"
 */
class CancelRecurringRequest extends AbstractTransactionModificationRequest
{
    /**
     * Flag as a Cancel Recurring
     *
     * @var int;
     */
    protected $modificationType = 4;
}
