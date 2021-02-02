<?php

namespace Restruct\SilverStripe\LiveSeo\Extensions {

    use SilverStripe\Core\Config\Config;
    use SilverStripe\Dev\Debug;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\LiteralField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\ORM\DataExtension;
    use SilverStripe\View\ArrayData;
    use SilverStripe\View\SSViewer;
    use SS_Object;
    use Exception;
    use Wilr\GoogleSitemaps\GoogleSitemap;

    class SeoSiteConfigExtension extends DataExtension
    {

        private static $db = [
            'SEOTitleTemplate' => 'Varchar(255)',
        ];

        public function updateCMSFields(FieldList $fields)
        {
            // check for Google Sitemaps module & notification;
            $GSMactive = Config::inst()->get(GoogleSitemap::class, 'enabled');
            $GSMping = Config::inst()->get(GoogleSitemap::class, 'google_notification_enabled');

            // check for Redirectmanager
            //<--$RedirActive = ContentController::has_extension(RedirectedURLHandler::class);
            $RedirActive = false;
            //$template = new SSViewer('AdminSiteConfigSeoTips');
            //$seotips = $template->process($this->owner->customise(new ArrayData(array(
            $seotips = $this->owner->customise(new ArrayData([
                //'ShowError' => true,
                'GSMactive'   => $GSMactive,
                'GSMping'     => $GSMping,
                'RedirActive' => $RedirActive,
//				'Pages' => new ArrayList(array_reverse($pages))
                //))));
            ]))->renderWith('Restruct\\Silverstripe\\LiveSeo\\Includes\\AdminSiteConfigSeoTips');

            $fields->addFieldToTab("Root.Main", LiteralField::create('SEOtips', $seotips));

            // SEOTITLE
            // parse out the title tag as used by the theme;


            /*
            $loader = TemplateLoader::instance();
            $theme = Config::inst()->get(SSViewer::class, 'theme');
            $foundpath = $loader->findTemplates("main/Page", $theme); // TODO: this is a guess...
            $path = $foundpath[ 'main' ];
            if ( file_exists($path) ) {
                $templatecode = file_get_contents($path);
            } else {
                throw new Exception('Failed to identify path');
            }
            if ( $templatecode && strpos($templatecode, '<title>') ) {
                $templatetag = explode('<title>', $templatecode);
                $templatetag = array_pop($templatetag);
                $templatetag = explode('</title>', $templatetag);
                $templatetag = array_shift($templatetag);
            } else {
                $templatetag = false;
            }
//		$template = SSViewer::fromString($titlehtml);
//		$fulltitle = $template->process($this->owner);

            */

            $templatetag = "<title>{$this->owner->Title}</title>";

            if ( $templatetag ) {
                $templatetag = "<br />Current template title tag: " . $templatetag;
            } else {
                $templatetag = "";
            }

            // FIXME - not sure of the intention of this value, but set it to avoid site config breaking
            $titlehtml = '';

            $fields->addFieldToTab("Root.Main", $seotitlefield = TextField::create('SEOTitleTemplate')
                ->setDescription("For SEO preview (valid js expression, available vars: page_title, page_menutitle, '.
					'page_metadata_title), eg:<br /> page_title + ' &raquo; ' +
				siteconfig_title [OR] (page_metadata_title ? page_metadata_title : page_title)" . $titlehtml));

            // set default/initial value
            if ( !$this->owner->SEOTitleTemplate ) {
                $seotitlefield->setValue("page_title + ' &raquo; ' + siteconfig_title");
            }
        }
    }
}
