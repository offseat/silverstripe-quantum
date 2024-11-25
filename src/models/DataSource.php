<?php

namespace Silverstripe\Quantum\Model;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataObject;
use Symbiote\MultiValueField\Fields\MultiValueTextField;

class DataSource extends DataObject
{
    private static $table_name = 'DataSource';

    private static $icon = 'font-icon-block-form';

    private static $singular_name = 'DataSource';

    private static $plural_name = 'DataSources';

    private static $db = [
        'Name' => 'Varchar',
        'Route' => 'Varchar',
        'FieldNames' => 'MultiValueField',
    ];

    private static $has_many = [
        'Values' => DataEntity::class,
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
        if (sizeof($data)) {
            $mergedArray = [];
            foreach ($data as $key => $value) {
                array_push($mergedArray,json_decode($value, true));
            }
        }

        return $mergedArray ;
    }
}
