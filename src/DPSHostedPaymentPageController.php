<?php

namespace SilverStripe\PaymentDpsHosted;

use PageController;

/**
 * @package payment_dpshosted
 */
class DPSHostedPaymentPageController extends PageController
{
    /**
     * @var array
     */
    private static $allowed_actions = [
        'success',
        'error',
    ];

    public function Form()
    {
        $formClass = DPSHostedPayment::$payment_form_class;
        return new $formClass(
            $this,
            'Form'
        );
    }

    public function success()
    {
        return $this->customise([
            'Content' => $this->SuccessContent,
            'Form'    => ' ',
        ])->renderWith('Page');
    }

    public function error()
    {
        return $this->customise([
            'Content' => $this->ErrorContent,
            'Form'    => ' ',
        ])->renderWith('Page');
    }
}
