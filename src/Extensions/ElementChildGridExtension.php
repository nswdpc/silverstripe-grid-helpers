<?php

namespace NSWDPC\GridHelper\Extensions;

use NSWDPC\GridHelper\Models\Configuration;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;

/**
 * Apply column handling for layout of child items
 * Applied to ElementList by default, you can apply it to other Elements that contain child items
 * to be rendered into a grid/list or similar template
 * @author James Ellis
 * @property int $CardColumns
 * @extends \SilverStripe\ORM\DataExtension<static>
 */
class ElementChildGridExtension extends DataExtension
{
    /**
     * DB fields
     */
    private static array $db = [
        'CardColumns' => 'Int' // grid columns at lg breakpoint
    ];

    /**
     * Get the grid configurator model
     */
    protected function getConfigurator(): Configuration
    {
        return Injector::inst()->get(Configuration::class);
    }

    public function updateCMSFields(FieldList $fields)
    {

        $options = $this->getConfigurator()->config()->get('card_columns');
        $options = is_array($options) ? array_unique($options) : [];

        $cardColumns = DropdownField::create(
            'CardColumns',
            _t('gridhelpers.COLUMNS', 'Columns'),
            $options
        )->setEmptyString(_t('gridhelpers.NOT_SET','not set'))
        ->setDescription(
            _t(
                'gridhelpers.CHOOSE_THE_NUMBER_OF_COLUMNS',
                'Choose the number of horizontal columns in this grid. 1 = full width'
            )
        );

        $fields->addFieldsToTab(
            'Root.Display',
            [
                $cardColumns
            ]
        );

    }

    /**
     * Ensure default column count is written if not set
     * Allow the owner class to set it's own default via configuration
     */
    public function onBeforeWrite()
    {
        /** @phpstan-ignore property.notFound */
        if (!$this->getOwner()->CardColumns) {
            $defaultLargeColumnCount = Configuration::config()->get('default_lg_column_count');
            $ownerDefaultCount = Config::inst()->get($this->getOwner()::class, 'grid_default_lg_column_count');
            if ($ownerDefaultCount) {
                $defaultLargeColumnCount = $ownerDefaultCount;
            }

            $this->getOwner()->CardColumns = $defaultLargeColumnCount;
        }
    }

    /**
     * This method is retained for BC
     */
    public function getColumns(): string
    {
        /** @phpstan-ignore property.notFound */
        return $this->getOwner()->ColumnClass($this->getOwner()->CardColumns);
    }

    /**
     * Return the CSS class representing a grid
     * @param ?int $lg the number of large columns eg 3. Default=null meaning defer to the selected CardColumns value
     * @param int $max the max grid size. Used to work out the CSS class. $max/$lg =  grid 'width'
     * @param int $xs number of columns at XS media size, default = 1 col @ 100% width
     * @param int $sm number of columns at SM media size, default = 2 cols @ 50% width
     * @param int $md number of columns at MD media size, default = 3 cols @ 33.3% width
     * @param ?int $xl number of columns at XL media size, if supported, default = none
     */
    public function ColumnClass(?int $lg = null, int $max = 12, int $xs = 1, int $sm = 2, int $md = 3, ?int $xl = null): string
    {

        $desktopColumns = null;
        /** @phpstan-ignore property.notFound */
        $cardColumns = $this->getOwner()->CardColumns;
        if (is_int($lg)) {
            $desktopColumns = abs($lg);
        } elseif (is_int($cardColumns)) {
            $desktopColumns = abs($cardColumns);
        }

        $max = abs($max);
        $xs = abs($xs);
        $sm = abs($sm);
        $md = abs($md);

        if ($max <= 0) {
            $max = $this->getConfigurator()->config()->get('max_columns');
        }

        if (is_int($xl)) {
            $xl = abs($xl);
        }

        if (is_null($desktopColumns) || $desktopColumns == 0) {
            return '';
        } else {

            $xs = $this->getConfigurator()->getGridValue($xs, $desktopColumns);
            $sm = $this->getConfigurator()->getGridValue($sm, $desktopColumns);
            $md = $this->getConfigurator()->getGridValue($md, $desktopColumns);

            $gridLg = ceil($max / $desktopColumns);
            $gridXs = ceil($max / $xs);
            $gridSm = ceil($max / $sm);
            $gridMd = ceil($max / $md);

            $grids = [
                $this->getConfigurator()->ColumnMapping("xs") . "-{$gridXs}",
                $this->getConfigurator()->ColumnMapping("sm") . "-{$gridSm}",
                $this->getConfigurator()->ColumnMapping("md") . "-{$gridMd}",
                $this->getConfigurator()->ColumnMapping("lg") . "-{$gridLg}"
            ];

            $gridXl = null;
            if (is_int($xl) && $xl > 0) {
                $xl = $this->getConfigurator()->getGridValue($xl, $desktopColumns);
                $gridXl = ceil($max / $xl);
            }

            if (!is_null($gridXl)) {
                $grids[] = $this->getConfigurator()->ColumnMapping("xl") . "-{$gridXl}";
            }

            return implode(" ", $grids);
        }

    }

}
