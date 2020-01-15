<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use DiDom\Document;
use App\Domain;

class CollectAdditionalData extends Job
{
    private $domain;

    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function handle(Client $client)
    {
        try {
            $clientsResponse = $client->get($this->domain->name);
            $responseStatus = $clientsResponse->getStatusCode() ?? null;
            $responseBody = (string) $clientsResponse->getBody() ?? null;
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

            Domain::whereId($this->domain->id)
                                ->update([
                                    'status' => $responseStatus,
                                    'content_length' => $responseContentLength,
                                    'body' => $responseBody,
                                    'header1' => $firstHeader,
                                    'description' => $description,
                                    'keywords' => $keywords,
                                ]);
            $this->domain->stateMachine()->apply('process');
            $this->domain->save();
        } catch (\Exception $e) {
            $this->domain->stateMachine()->apply('error');
            $this->domain->save();
        }
    }
}
