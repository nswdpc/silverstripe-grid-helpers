<?php

namespace NSWDPC\GridHelper\Extensions;

use Silverstripe\ORM\DataExtension;
use Silverstripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;

/**
 * Extension applied to Elements that can contain other elements
 * @author James Ellis
 * @author Mark Taylor
 */
class ElementHasChildrenExtension extends DataExtension
{

    public function ElementHasChildren() {
        return true;
    }

    /**
     * DB fields for the list element
     * @var array
     */
    private static $db = [
        'Subtype' => 'Varchar(64)',
        'CardStyle' => 'Varchar(64)'
    ];

    /**
     * Available types of listings
     * @var array
     */
    private static $subtypes = [
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
     * @var array
     */
    private static $defaults = [
        'Subtype' => '',// no default
        'CardStyle' => 'title-abstract'
    ];

    /**
     * Used to **hint* how list child elements are rendered.
     * A template can use a key value from here to determine how to render the child items
     * @var array
     */
    private static $card_styles = [
        'title' => 'Title only',
        'title-abstract' => 'Title and abstract',
        'title-image-abstract' => 'Title, image, abstract',
        'promo' => 'Promo'
    ];

    public function updateCMSFields(FieldList $fields)
    {

        // the subtype
        $options = $this->owner->config()->get('subtypes');
        $options = is_array($options) ? array_unique($options) : [];
        $subType = DropdownField::create(
            'Subtype',
            _t('gridhelpers.LISTTYPE','List type'),
            $options
        );
        $subType->setEmptyString('none');

        // card style, if appropriate
        $options = $this->owner->config()->get('card_styles');
        $options = is_array($options) ? array_unique($options) : [];
        $cardStyle = DropdownField::create(
            'CardStyle',
            _t('gridhelpers.CARDSTYLE','Content style'),
            $options
        );
        $cardStyle->setEmptyString('none');
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
