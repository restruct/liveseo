<?php

namespace Restruct\SilverStripe\LiveSeo {


    use SilverStripe\Reports\Report;
    use Page;

    class SeoReport extends Report
    {
        public function title()
        {
            return 'SEO';
        }

        public function sourceRecords($params = null)
        {
            return Page::get()->sort('SEOPageScore');
        }

        public function columns()
        {
            $fields = [
                "Title"          => [
                    "title" => "Title", // todo: use NestedTitle(2)
                    "link"  => true,
                ],
                'MetaTitle'      => 'Meta Title',
                'SEOPageSubject' => 'SEO Page Subject',
                'SEOPageScore'   => 'SEO Score',
            ];

            return $fields;
        }
    }
}
