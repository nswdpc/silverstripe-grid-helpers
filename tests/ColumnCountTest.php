<?php

namespace NSWDPC\GridHelper\Tests;

use NSWDPC\GridHelper\Extensions\ElementChildGridExtension;
use NSWDPC\GridHelper\Models\Configuration;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;

/**
 * Provide tests for a single gallery video
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

        var_dump($obj->CardColumns);

        $this->assertEquals($defaultLargeColumnCount, 4);
    }

}
