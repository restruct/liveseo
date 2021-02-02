<?php

namespace Restruct\SilverStripe\LiveSeo\Fields {

    use SilverStripe\Forms\FormField;
    use SilverStripe\View\Requirements;


    /**
 * Field which gets suggestions from google search
 */
class GoogleSuggestField extends FormField
{

    public function Field($properties = array())
    {
        Requirements::javascript( "restruct/silverstripe-liveseo:client/dist/js/googlesuggestfield.js");

        $this->addExtraClass('text');

        return parent::Field($properties);
    }
}
}
