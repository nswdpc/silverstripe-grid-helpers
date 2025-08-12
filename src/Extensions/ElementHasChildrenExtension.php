<?php

namespace NSWDPC\GridHelper\Extensions;

use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;

/**
 * Extension applied to Elements that can contain other elements
 * @author James Ellis
 * @author Mark Taylor
 * @property ?string $Subtype
 * @property ?string $CardStyle
 * @extends \SilverStripe\ORM\DataExtension<static>
 */
class ElementHasChildrenExtension extends DataExtension
{
    public function ElementHasChildren(): bool
    {
        return true;
    }

    /**
     * DB fields for the list element
     */
    private static array $db = [
        'Subtype' => 'Varchar(64)',
        'CardStyle' => 'Varchar(64)'
    ];

    /**
     * Available types of listings
     */
    private static array $subtypes = [
        'accordion' => 'Accordion',
        'cards' => 'Cards',
        'carousel' => 'Carousel',
        'content-blocks' => 'Content Blocks',
        'grid' => 'Grid',
        'linklist' => 'Link list',
        'listitem' => 'List items',
        'media-images' => 'Media (images)',
        'tabs' => 'Tabs',
    ];

    /**
     * Default values
     */
    private static array $defaults = [
        'Subtype' => '',// no default
        'CardStyle' => 'title-abstract'
    ];

    /**
     * Used to **hint* how list child elements are rendered.
     * A template can use a key value from here to determine how to render the child items
     */
    private static array $card_styles = [
        'title' => 'Title only',
        'title-abstract' => 'Title and abstract',
        'title-image-abstract' => 'Title, image, abstract',
        'promo' => 'Promo'
    ];

    public function updateCMSFields(FieldList $fields)
    {

        // the subtype
        $options = Config::inst()->get($this->getOwner()::class, 'subtypes');
        $options = is_array($options) ? array_unique($options) : [];

        $subType = DropdownField::create(
            'Subtype',
            _t('gridhelpers.LISTTYPE', 'List type'),
            $options
        );
        $subType->setEmptyString(_t('gridhelpers.NONE','none'));

        // card style, if appropriate
        $options = Config::inst()->get($this->getOwner()::class, 'card_styles');
        $options = is_array($options) ? array_unique($options) : [];

        $cardStyle = DropdownField::create(
            'CardStyle',
            _t('gridhelpers.CARDSTYLE', 'Content style'),
            $options
        );
        $cardStyle->setEmptyString(_t('gridhelpers.NONE','none'));
        $cardStyle->displayIf('Subtype')
            ->isEqualTo('cards')
            ->orIf("Subtype")->isEqualTo("carousel")
            ->orIf("Subtype")->isEqualTo("content-blocks")
            ->orIf("Subtype")->isEqualTo("listitem");

        $fields->addFieldsToTab(
            'Root.Display',
            [
                $subType,
                $cardStyle
            ]
        );

    }
}
