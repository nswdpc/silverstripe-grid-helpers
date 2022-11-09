<?php

namespace NSWDPC\GridHelper\Tests;

use NSWDPC\GridHelper\Extensions\ElementChildGridExtension;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

/**
 * Model to test column class handling
 * @author James
 */
class ColumnClassModel extends DataObject implements TestOnly {

    /**
     * @inheritdoc
     */
    private static $table_name = "ColumnClassModel";

    /**
     * @inheritdoc
     */
    private static $db = [
        'Title' => 'Varchar(255)'
    ];

}
