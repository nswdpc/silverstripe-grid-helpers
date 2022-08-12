<?php

namespace NSWDPC\GridHelper\Tests;

use NSWDPC\GridHelper\Extensions\ElementChildGridExtension;
use NSWDPC\GridHelper\Models\Configuration;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;

/**
 * Column count test
 */
class ColumnCountTest extends SapphireTest {

    protected $usesDatabase =  true;

    /**
     * @inheritdoc
     */
    protected static $extra_dataobjects = [
        ColumnCountModel::class
    ];

    /**
     * @inheritdoc
     */
    protected static $required_extensions = [
        ColumnCountModel::class => [
            ElementChildGridExtension::class
        ]
    ];

    public function testColumnCount() {
        $defaultLargeColumnCount = Configuration::config()->get('default_lg_column_count');
        $obj = ColumnCountModel::create([
            'Title' => 'Test model'
        ]);
        $obj->write();
        $this->assertEquals($defaultLargeColumnCount, $obj->CardColumns);
    }

    public function testColumnSpecificCount() {
        $defaultLargeColumnCount = Configuration::config()->get('default_lg_column_count');
        $specificCount = 2;
        Config::modify()->set(ColumnCountModel::class, 'grid_default_lg_column_count', $specificCount);
        $obj = ColumnCountModel::create([
            'Title' => 'Test model with specific column count'
        ]);
        $obj->write();
        $this->assertEquals($specificCount, $obj->CardColumns);
    }

}
