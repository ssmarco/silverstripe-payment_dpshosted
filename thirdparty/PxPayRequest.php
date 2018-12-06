<?php
/**
 * @package payment_dpshosted
 * @see http://civicrm.org
 */
#******************************************************************************
# Class for PxPay request messages.
#******************************************************************************
class PxPayRequest extends PxPayMessage
{
    var $TxnId, $UrlFail, $UrlSuccess;
    var $AmountInput, $AppletVersion, $CurrencyInput;
    var $EnableAddBillCard;
    var $TS;
    var $PxPayUserId;
    var $PxPayKey;

    var $AppletType;

    #Constructor
    public function __construct()
    {
        parent::__construct();

    }

    public function getAppletType()
    {
        return $this->AppletType;
    }

    public function setAppletType($AppletType)
    {
        $this->AppletType = $AppletType;
    }

    public function setTs($Ts)
    {
        $this->TS = $Ts;
    }

    public function getEnableAddBillCard()
    {
        return $this->EnableAddBillCard;
    }

    public function setEnableAddBillCard($EnableBillAddCard)
    {
        $this->EnableAddBillCard = $EnableBillAddCard;
    }

    public function setInputCurrency($InputCurrency)
    {
        $this->CurrencyInput = $InputCurrency;
    }

    public function getInputCurrency()
    {
        return $this->CurrencyInput;
    }

    public function getTxnId()
    {
        return $this->TxnId;
    }

    public function setTxnId($TxnId)
    {
        $this->TxnId = $TxnId;
    }

    public function setUrlFail($UrlFail)
    {
        $this->UrlFail = $UrlFail;
    }

    public function getUrlFail()
    {
        return $this->UrlFail;
    }

    public function setUrlSuccess($UrlSuccess)
    {
        $this->UrlSuccess = $UrlSuccess;
    }

    public function getAmountInput()
    {

        return $this->AmountInput;
    }

    public function setAmountInput($AmountInput)
    {
        $this->AmountInput = sprintf("%9.2f", $AmountInput);
    }

    public function setUserId($UserId)
    {
        $this->PxPayUserId = $UserId;
    }

    public function setKey($Key)
    {
        $this->PxPayKey = $Key;
    }

    public function setSwVersion($SwVersion)
    {
        $this->AppletVersion = $SwVersion;
    }

    public function getSwVersion()
    {
        return $this->AppletVersion;
    }
    #******************************************************************
    #Data validation
    #******************************************************************
    public function validData()
    {
        $msg = "";
        if ($this->TxnType != "Purchase") {
            if ($this->TxnType != "Auth") {
                if ($this->TxnType != "GetCurrRate") {
                    if ($this->TxnType != "Refund") {
                        if ($this->TxnType != "Complete") {
                            if ($this->TxnType != "Order1") {
                                $msg = "Invalid TxnType[$this->TxnType]<br>";
                            }
                        }
                    }
                }
            }
        }

        if (strlen($this->MerchantReference) > 64) {
            $msg = "Invalid MerchantReference [$this->MerchantReference]<br>";
        }

        if (strlen($this->TxnId) > 16) {
            $msg = "Invalid TxnId [$this->TxnId]<br>";
        }
        if (strlen($this->TxnData1) > 255) {
            $msg = "Invalid TxnData1 [$this->TxnData1]<br>";
        }
        if (strlen($this->TxnData2) > 255) {
            $msg = "Invalid TxnData2 [$this->TxnData2]<br>";
        }
        if (strlen($this->TxnData3) > 255) {
            $msg = "Invalid TxnData3 [$this->TxnData3]<br>";
        }

        if (strlen($this->EmailAddress) > 255) {
            $msg = "Invalid EmailAddress [$this->EmailAddress]<br>";
        }

        if (strlen($this->UrlFail) > 255) {
            $msg = "Invalid UrlFail [$this->UrlFail]<br>";
        }
        if (strlen($this->UrlSuccess) > 255) {
            $msg = "Invalid UrlSuccess [$this->UrlSuccess]<br>";
        }
        if (strlen($this->BillingId) > 32) {
            $msg = "Invalid BillingId [$this->BillingId]<br>";
        }
        if (strlen($this->DpsBillingId) > 16) {
            $msg = "Invalid DpsBillingId [$this->DpsBillingId]<br>";
        }

        if ($msg != "") {
            trigger_error($msg, E_USER_ERROR);
            return false;
        }
        return true;
    }

}
