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
    private static string $table_name = "ColumnCountModel";

    /**
     * @inheritdoc
     */
    private static array $db = [
        'Title' => 'Varchar(255)'
    ];

}
