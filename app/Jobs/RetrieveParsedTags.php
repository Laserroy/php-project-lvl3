<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use DiDom\Document;

class RetrieveParsedTags extends Job
{
    private $recordId;

    public function __construct($recordId)
    {
        $this->recordId = $recordId;
    }

    public function handle()
    {
        $html = DB::table('domains')->where('id', $this->recordId)->value('body');
        $document = new Document($html);

        $firstHeader = null;
        $description = null;
        $keywords = null;
        
        if ($document->has('h1')) {
            $firstHeader = $document->first('h1')->text();
        }

        if ($document->has('meta[name*=keywords]')) {
            $keywords = $document->first('meta[name*=keywords]')->getAttribute('content');
        }

        if ($document->has('meta[name*=description]')) {
            $description = $document->first('meta[name*=description]')->getAttribute('content');
        }

        DB::table('domains')->where('id', '=', $this->recordId)
                                ->update(
                                    ['header1' => $firstHeader,
                                     'description' => $description,
                                     'keywords' => $keywords,
                                     'record_state' => 'complete']
                                );
    }
}
