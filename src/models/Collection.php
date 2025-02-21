<?php

namespace Silverstripe\Quantum\Model;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\PermissionFailureException;
use Symbiote\MultiValueField\Fields\KeyValueField;
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

    const fieldtypes = [
        'TextField',
        'TextareaField',
        'HTMLEditorField',
        'CheckboxField',
        'DropdownField',
        'NumericField',
        'EmailField',
        'DateField',
        'TimeField',
        'DatetimeField',
        'OptionsetField',
        'GridField',
        'ReadonlyField',
        'FileField',
        'TreeDropdownField',
        'CompositeField',
        'FieldGroup',
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
           KeyValueField::create('FieldNames', 'Field Names', self::fieldtypes),
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

    /**
     * Returns all fields on the object which should be shown
     * in the output.
     */
    protected function getFieldsAsJson(): string {
        $dbFields = DataObject::getSchema()->fieldSpecs(self::class);
        return json_encode(array_keys($dbFields));
    }
}
