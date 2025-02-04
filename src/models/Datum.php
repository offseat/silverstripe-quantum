<?php

namespace Silverstripe\Quantum\Model;

use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Member;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Security;

class Datum extends DataObject
{
    private static $table_name = 'Datum';

    private static $icon = 'font-icon-block-form';

    private static $singular_name = 'Data';

    private static $plural_name = 'Data';

    private static $db = [
        'PreparedData' => 'Text',
    ];

    private static $has_one = [
        'SubmittedBy' => Member::class,
        'Parent' => DataObject::class,
    ];

    private static $summary_fields = [
        'PreparedData'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        if ($this->SubmittedBy() && $this->SubmittedBy()->exists()) {
            $submitter = $this->SubmittedBy()->Email;
        } else {
            $submitter = null;
        }

        //replace scaffolded field with readonly submitter
        $fields->replaceField(
            'SubmittedByID',
            ReadonlyField::create('Submitter', _t(__CLASS__ . '.SUBMITTER', 'Submitter'), $submitter)
                ->addExtraClass('form-field--no-divider')
        );

        $fields->replaceField(
            'PreparedData',
            ReadonlyField::create('Prepared Data', '', $this->PreparedData)
        );
        $data = $this->PreparedData ? json_decode($this->PreparedData, true): [];
        
        foreach($this->Parent()->data()->FieldNames->getValues() as $field) {
            $fieldValue = $data[$field] ?? '';
            $fields->addFieldToTab(
                'Root.Main',
                TextField::create($field)->setValue($fieldValue)
            );
        }
   
        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        
        $this->SubmittedByID = Security::getCurrentUser()->ID;

        $data = [];
        foreach ($this->Parent()->data()->FieldNames->getValues() as $field) {
            if (isset($this->record[$field])) {
                $data[$field] = $this->record[$field];
            }
        }

        $this->PreparedData = json_encode($data);
    }

}
