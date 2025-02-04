<?php

namespace Silverstripe\Quantum\Model;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\PermissionFailureException;
use Symbiote\MultiValueField\Fields\MultiValueTextField;

class Collection extends DataObject
{
    private static $table_name = 'Collection';

    private static $icon = 'font-icon-block-form';

    private static $singular_name = 'Collection';

    private static $plural_name = 'Collections';

    private static $db = [
        'Name' => 'Varchar',
        'Route' => 'Varchar',
        'FieldNames' => 'MultiValueField',
    ];

    private static $has_many = [
        'Values' => Datum::class,
    ];

    private static $summary_fields = [
        'Name',
        'Route',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Values');

        $config = GridFieldConfig_RecordEditor::create();
        $entities = GridField::create(
            'Values',
            '',
            $this->Values()->sort('Created', 'DESC'),
            $config
        );

        $fields->addFieldsToTab('Root.Main', [
            $entities,
            MultiValueTextField::create('FieldNames', 'Field Names'),
        ]);

        return $fields;
    }

    public function getPreparedData(): array
    {
        $data = $this->Values()->sort('Created', 'DESC')->column('PreparedData') ?? [];
        $mergedArray = [];

        if (sizeof($data)) {
            foreach ($data as $key => $value) {
                array_push($mergedArray,json_decode($value, true));
            }
        }

        return $mergedArray ;
    }

    private function canViewCollection($member = null)
    {
        if (!$member) return new PermissionFailureException;

        return false;

    }
}
