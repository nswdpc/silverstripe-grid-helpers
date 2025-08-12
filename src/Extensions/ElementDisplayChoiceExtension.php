<?php

namespace NSWDPC\GridHelper\Extensions;

use DNADesign\Elemental\Models\ElementalArea;
use DNADesign\ElementalList\Model\ElementList;
use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\LiteralField;

/**
 * Apply display choices options to an Element
 * @property ?string $Subtype
 * @extends \SilverStripe\ORM\DataExtension<static>
 */
class ElementDisplayChoiceExtension extends DataExtension
{
    /**
     * Database fields
     */
    private static array $db = [
        'Subtype' => 'Varchar(64)'
    ];

    private static array $subtypes = [
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

        $inList = $this->getOwner()->isWithinElementList();
        if ($inList) {
            $fields->removeByName(['Subtype']);
            $fields->addFieldToTab(
                'Root.Display',
                LiteralField::create(
                    'Subtype_Message',
                    '<p class="message info">'
                    . htmlspecialchars(_t(
                        'gridhelpers.ELEMENT_IN_LIST',
                        'This element is within a list, which sets the display options'
                    ))
                    . '</p>'
                )
            );
        } else {
            $displayOptions = Config::inst()->get($this->getOwner()::class, 'subtypes');
            if (!is_array($displayOptions)) {
                $displayOptions = [];
            }

            $fields->addFieldToTab(
                'Root.Display',
                DropdownField::create(
                    'Subtype',
                    _t('gridhelpers.DISPLAY_OPTIONS', 'Display option'),
                    $displayOptions
                )
                ->setEmptyString(_t('gridhelpers.NONE', 'none'))
            );
        }

    }

    /**
     * Determine if this element is within a list, which will set the display requirements if so
     */
    public function isWithinElementList(): bool
    {

        if (!class_exists(ElementList::class)) {
            return false;
        }

        /** @phpstan-ignore method.notFound */
        $parent = $this->getOwner()->Parent();
        if (!$parent || !($parent instanceof ElementalArea)) {
            return false;
        }

        $list = ElementList::get()->filter(['ElementsID' => $parent->ID])->first();
        return $list && $list instanceof ElementList;
    }

    /**
     * Remove StyleVariant from elements
     */
    public function updateStyleVariant(): string
    {
        return "";
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        // clear these default settings
        /** @phpstan-ignore property.notFound */
        $this->getOwner()->ExtraClass = '';
        /** @phpstan-ignore property.notFound */
        $this->getOwner()->Style = '';
    }

}
