<?php

namespace SilverStripe\PaymentDpsHosted;

use Page;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\ORM\Connect\DBSchemaManager;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;

/**
 * @package payment_dpshosted
 */
class DPSHostedPaymentPage extends Page
{
    /**
     * @var string
     */
    private static $singular_name = 'DPS payment confirmation page';

    /**
     * @var string
     */
    private static $plural_name = 'DPS payment confirmation pages';

    /**
     * @var array
     */
    private static $db = [
        'SuccessContent' => 'HTMLText',
        'ErrorContent'   => 'HTMLText',
    ];

    /**
     * @var array
     */
    private static $defaults = [
        'SuccessContent' => 'Thank you',
        'ErrorContent'   => 'There has been an error.',
    ];

    /**
     * @var string
     */
    private static $table_name = 'DPSHostedPaymentPage';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeFieldFromTab('Root.Main', 'Content');

        $fields->addFieldsToTab(
            'Root.Main',
            [
                HtmlEditorField::create('SuccessContent', 'Success message')  ,
                HtmlEditorField::create('ErrorContent', 'Error message'),
            ]
        );

        return $fields;
    }

    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        if (!DataObject::get_one(DPSHostedPaymentPage::class)) {
            $page = new DPSHostedPaymentPage();
            $page->Title = "Payment Status";
            $page->URLSegment = "paymentstatus";
            $page->ShowInMenus = 0;
            $page->ShowInSearch = 0;
            $page->write();

            DB::get_schema()->alterationMessage('DPSHostedPaymentPage page created', 'created');
        }
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return DPSHostedPaymentController::class;
    }
}
