<?php

namespace NSWDPC\GridHelper\Tests;

use NSWDPC\GridHelper\Extensions\ElementChildGridExtension;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

/**
 * Model to test column count handling
 * @author James
 */
class ColumnCountModel extends DataObject implements TestOnly {

    /**
     * @inheritdoc
     */
    private static $table_name = "ColumnCountModel";

    /**
     * @inheritdoc
     */
    private static $db = [
        'Title' => 'Varchar(255)'
    ];

}
