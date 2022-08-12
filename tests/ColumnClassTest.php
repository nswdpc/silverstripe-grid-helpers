<?php

namespace NSWDPC\GridHelper\Tests;

use NSWDPC\GridHelper\Extensions\ElementChildGridExtension;
use NSWDPC\GridHelper\Models\Configuration;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;

/**
 * Column class test
 */
class ColumnClassTest extends SapphireTest {

    protected $usesDatabase =  true;

    /**
     * @inheritdoc
     */
    protected static $extra_dataobjects = [
        ColumnClassModel::class
    ];

    /**
     * @inheritdoc
     */
    protected static $required_extensions = [
        ColumnClassModel::class => [
            ElementChildGridExtension::class
        ]
    ];

    public function testColumnClass() {
        Config::modify()->set(Configuration::class, 'grid_prefix', 'test-grid');
        $defaultLargeColumnCount = Configuration::config()->get('default_lg_column_count');
        $obj = ColumnClassModel::create([
            'Title' => 'Test model'
        ]);
        $obj->write();
        $this->assertEquals($defaultLargeColumnCount, $obj->CardColumns);
        $columnClass = $obj->ColumnClass();
        $this->assertStringContainsString('test-grid-lg-3', $columnClass );
    }

    public function testColumnClassSpecificCount() {
        Config::modify()->set(Configuration::class, 'grid_prefix', 'test-grid');

        $specificCount = 2;
        Config::modify()->set(ColumnClassModel::class, 'grid_default_lg_column_count', $specificCount);

        $obj = ColumnClassModel::create([
            'Title' => 'Test model'
        ]);
        $obj->write();
        $this->assertEquals($specificCount, $obj->CardColumns);
        $columnClass = $obj->ColumnClass();
        $this->assertStringContainsString('test-grid-lg-6', $columnClass );
        $this->assertStringContainsString('test-grid-md-6', $columnClass );
        $this->assertStringContainsString('test-grid-sm-6', $columnClass );
    }

}
