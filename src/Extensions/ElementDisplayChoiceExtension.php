<?php

namespace NSWDPC\GridHelper\Extensions;

use DNADesign\Elemental\Models\ElementalArea;
use DNADesign\ElementalList\Model\ElementList;
use Silverstripe\ORM\DataExtension;
use Silverstripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\LiteralField;

/**
 * Apply display choices options to an Element
 */
class ElementDisplayChoiceExtension extends DataExtension
{

    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'Subtype' => 'Varchar(64)'
    ];

    private static $subtypes = [
        'callout' => 'Callout',
        'notification' => 'Notification',
        'global-alert' => 'Global alert',
        'media-image' => 'Media - image',
        'media-video' => 'Media - video',
        'profile' => 'Profile'
    ];


    public function updateCMSFields(FieldList $fields)
    {

        // remove these core fields provided by BaseElement
        $fields->removeByName(['Style','ExtraClass']);

        $inList = $this->owner->isWithinElementList();
        if($inList) {
            $fields->removeByName(['Subtype']);
            $fields->addFieldToTab(
                'Root.Display',
                LiteralField::create(
                    'Subtype_Message',
                    '<p class="message info">'
                        . _t('gridhelpers.ELEMENT_IN_LIST','This element is within a list, which sets the display options')
                    . '</p>'
                )
            );
        } else {
            // Add a
            $fields->addFieldToTab(
                'Root.Display',
                DropdownField::create(
                    'Subtype',
                    _t('gridhelpers.DISPLAY_OPTIONS','Display option'),
                    $this->owner->config()->get('subtypes')
                )
                ->setEmptyString('none')
            );
        }

    }

    /**
     * Determine if this element is within a list, which will set the display requirements if so
     * @return bool
     */
    public function isWithinElementList() : bool {

        $parent = $this->owner->Parent();
        if(!$parent || !($parent instanceof ElementalArea)) {
            return false;
        }

        $list = ElementList::get()->filter(['ElementsID' => $parent->ID])->first();
        if(!$list || !($list instanceof ElementList)) {
            return false;
        }

        return true;
    }

    /**
     * Remove StyleVariant from elements
     */
    public function updateStyleVariant() {
        return "";
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        // clear these default settings
        $this->owner->ExtraClass = '';
        $this->owner->Style = '';
    }

}
