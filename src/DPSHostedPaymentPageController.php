<?php

namespace SilverStripe\PaymentDpsHosted;

use PageController;
use SilverStripe\ORM\FieldType\DBHTMLText;

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

    /**
     * @return DBHTMLText
     */
    public function success()
    {
        $content = DBHTMLText::create();
        $content->setValue($this->SuccessContent);

        return $this->customise([
            'Content' => $content,
            'Form'    => ' ',
        ])->renderWith('Page');
    }

    /**
     * @return DBHTMLText
     */
    public function error()
    {
        $content = DBHTMLText::create();
        $content->setValue($this->ErrorContent);

        return $this->customise([
            'Content' => $content,
            'Form'    => ' ',
        ])->renderWith('Page');
    }
}
