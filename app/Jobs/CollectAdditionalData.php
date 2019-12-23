<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use DiDom\Document;

class CollectAdditionalData extends Job
{
    private $url;
    private $recordId;

    public function __construct($url, $recordId)
    {
        $this->url = $url;
        $this->recordId = $recordId;
    }

    public function handle(Client $client)
    {
        $clientsResponse = $client->get($this->url);
        $responseStatus = $clientsResponse->getStatusCode();
        $responseBody = (string) $clientsResponse->getBody();
        $responseContentLength = $clientsResponse->getHeader('Content-Length')[0] ?? null;

        $document = new Document($responseBody);

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

        DB::table('domains')->where('id', $this->recordId)
                            ->update(
                                ['status' => $responseStatus,
                                'content_length' => $responseContentLength,
                                'body' => $responseBody,
                                'header1' => $firstHeader,
                                'description' => $description,
                                'keywords' => $keywords,
                                'record_state' => 'complete']
                            );
    }
}
