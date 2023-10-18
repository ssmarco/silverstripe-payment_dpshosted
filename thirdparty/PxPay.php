<?php

/**
 * @package payment_dpshosted
 * @see http://civicrm.org
 */
class PxPay
{
    var $PxPay_Key;
    var $PxPay_Url;
    var $PxPay_Userid;

    public function __construct($Url, $UserId, $Key)
    {
        error_reporting(E_ERROR);
        $this->PxPay_Key = $Key;#pack("H*", $Key);
        $this->PxPay_Url = $Url;
        $this->PxPay_Userid = $UserId;
    }

    #******************************************************************************
    # Create an encoded request for the PxPay Host.
    #******************************************************************************
    public function makeRequest($request)
    {
        #Validate the Request
        if ($request->validData() == false) {
            return "";
        }

        //	$txnId = uniqid("MI");  #You need to generate you own unqiue reference.
        //	$request->setTxnId($txnId);
        $request->setTs($this->getCurrentTS());
        $request->setSwVersion("1.0");
        $request->setAppletType("PHPPxPay");
        $request->setUserId($this->PxPay_Userid);
        $request->setKey($this->PxPay_Key);
        $xml = $request->toXml();

        return $this->submitXml($xml);
    }

    /**
     * Actual submission of XML using cURL. Returns output XML
     * @param string $inputXml
     * @return string
     */
    public function submitXml($inputXml)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->PxPay_Url);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$inputXml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $outputXml = curl_exec ($ch);

        curl_close ($ch);

        return $outputXml;
    }

    #******************************************************************************
    # Return the decoded response from the PxPay Host.
    #******************************************************************************

    public function getCurrentTS()
    {

        return gmstrftime("%Y%m%d%H%M%S", time());
    }

    #******************************************************************************
    # Return the current time (GMT/UTC).The return time formatted YYYYMMDDHHMMSS.
    #******************************************************************************

    public function getResponse($resp_enc)
    {
        $xml = "<ProcessResponse><PxPayUserId>" . $this->PxPay_Userid . "</PxPayUserId><PxPayKey>" . $this->PxPay_Key . "</PxPayKey><Response>" . $resp_enc . "</Response></ProcessResponse>";
        $result = $this->submitXml($xml);
        $pxresp = new PxPayResponse($result);

        return $pxresp;
    }


}
