<?php

namespace SilverStripe\PaymentDpsHosted;

use SilverStripe\Forms\CurrencyField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;

/**
 * @package payment_dpshosted
 */
class DPSHostedPaymentForm extends Form
{

    /**
     * @var string $payment_class Subclass of DPSHostedPayment for custom processing
     */
    private static $payment_class = 'DPSHostedPayment';

    /**
     * DPSHostedPaymentForm constructor.
     *
     * @param $controller
     * @param $name
     */
    public function __construct($controller, $name)
    {
        $fields = new FieldList(
            $donationAmount = new CurrencyField("Amount", "Amount"),
            new TextField("FirstName", "First Name"),
            new TextField("Surname", "Surname"),
            $email = new EmailField("Email", "Email")
        );

        $actions = new FieldList(
            new FormAction("doPay", "Pay")
        );

        $validator = new RequiredFields([
            "Amount",
            "FirstName",
            "Surname",
            "Email",
        ]);

        parent::__construct($controller, $name, $fields, $actions, $validator);
    }

    /**
     * @param $data
     * @param $form
     */
    public function doPay($data, $form)
    {
        $paymentClass = self::$payment_class;
        $payment = new $paymentClass();

        // ensures that we just write data that was submitted through the form
        $form->saveInto($payment);

        $payment->setClientIP();
        $payment->write();
        $payment->processPayment($data, $form);
    }
}
