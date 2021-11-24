<?php

namespace NSWDPC\GridHelper\Models;

use SilverStripe\Core\Extensible;
use SilverStripe\Core\Config\Configurable;

/**
 * Base configuration model for the module.
 * You can extend and inject your own configuration model as required
 * @author James
 */
class Configuration {

    use Configurable;
    use Extensible;

    /**
     * The total number of grid columns in the frontend grid library
     * @var int
     */
    private static $max_columns = 12;

    /**
     * @var string
     */
    private static $grid_prefix = "nsw-col";

    /**
     * @var string
     */
    private static $grid_mapping = [
        "xs" => "xs",
        "sm" => "sm",
        "md" => "md",
        "lg" => "lg",
        "xl" => "xl",
    ];

    /**
     * Available columns to choose from
     * @var array
     */
    private static $card_columns = [
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '6' => '6'
    ];

    /**
     * @var bool
     * When true, grid columns > than the supplied desktop columns value
     * will be made = to the desktop value
     * When false, the values used will be as supplied by input
     * See
     */
    private static $sync_grid_to_desktop = true;

    /**
     * Work out and return the grid mapping based on prefix and a key
     * Use the config values to return the relevant values from configuration
     * To have more control over this, you should extend this class method and inject the class using Silverstripe's Injector.
     */
    public function ColumnMapping($key) : string {
        $prefix = $this->config()->get('grid_prefix');
        $mapping = $this->config()->get('grid_mapping');
        $breakpoint = !empty($mapping[ $key ]) ? $mapping[$key] : '';
        return $prefix . ($breakpoint ? "-{$breakpoint}" : "-");
    }

    /**
     * Determine grid value based on configuration and desktop value
     */
    public function getGridValue(int $cols, int $desktopColumns) : int {
        $sync = $this->config()->get('sync_grid_to_desktop');
        if(!$sync) {
            return $cols;
        }
        if($cols > $desktopColumns) {
            $cols = $desktopColumns;
        }
        if($cols == 0) {
            $cols = 1;
        }
        return $cols;
    }

}
