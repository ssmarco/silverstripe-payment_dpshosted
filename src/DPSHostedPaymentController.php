<?php

namespace SilverStripe\PaymentDpsHosted;

use PxPay;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;

class DPSHostedPaymentController extends Controller
{
    /**
     * @var array
     */
    private static $allowed_actions = [
        'processResponse',
    ];

    /**
     * React to DSP response triggered by {@link processPayment()}.
     */
    public function processResponse()
    {
        if (preg_match('/^PXHOST/i', $_SERVER['HTTP_USER_AGENT'])) {
            $dpsDirectlyConnecting = 1;
        }

        // @todo more solid page detection (check if published)
        $page = DataObject::get_one('DPSHostedPaymentPage');

        //$pxaccess = new PxAccess($PxAccess_Url, $PxAccess_Userid, $PxAccess_Key, $Mac_Key);

        $pxpay = new PxPay(
            DPSHostedPayment::$pxPay_Url,
            DPSHostedPayment::get_px_pay_userid(),
            DPSHostedPayment::get_px_pay_key()
        );

        $enc_hex = $_REQUEST["result"];

        $rsp = $pxpay->getResponse($enc_hex);

        if (isset($dpsDirectlyConnecting) && $dpsDirectlyConnecting) {
            // DPS Service connecting directly
            $success = $rsp->getSuccess();   # =1 when request succeeds
            echo ($success == '1') ? "success" : "failure";
        } else {
            // Human visitor
            $paymentID = $rsp->getTxnId();
            $SQL_paymentID = (int)$paymentID;

            $payment = DataObject::get_one('DPSHostedPayment', "`TxnID` = '$SQL_paymentID'");
            if (!$payment) {
                // @todo more specific error messages
                $redirectURL = $page->Link() . '/error';
                $this->redirect($redirectURL);
            }

            $success = $rsp->getSuccess();
            if ($success == '1') {
                // @todo Use AmountSettlement for amount setting?
                $payment->TxnRef = $rsp->getDpsTxnRef();
                $payment->Status = "Success";
                $payment->AuthorizationCode = $rsp->getAuthCode();
                $redirectURL = $page->Link() . '/success';

            } else {
                $payment->Message = $rsp->getResponseText();
                $payment->Status = "Failure";
                $redirectURL = $page->Link() . '/error';
            }
            $payment->write();
            $this->redirect($redirectURL);

        }
    }
}
