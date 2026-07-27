<?php

declare(strict_types=1);

namespace NSWDPC\GridHelper\Tests;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

/**
 * Model to test column class handling
 * @author James
 */
class ColumnClassModel extends DataObject implements TestOnly
{
    /**
     * @inheritdoc
     */
    private static string $table_name = "ColumnClassModel";

    /**
     * @inheritdoc
     */
    private static array $db = [
        'Title' => 'Varchar(255)'
    ];

}
