<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class RetreiveRequestData extends Job
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
        if (DB::table('domains')->where('id', $this->recordId)->where('record_state', 'init')->exists()) {
            $clientsResponse = $client->get($this->url);
            $responseStatus = $clientsResponse->getStatusCode();
            $responseBody = $clientsResponse->getBody();
            $responseContentLength = $clientsResponse->getHeader('Content-Length')[0] ?? 0;

            DB::table('domains')->where('id', '=', $this->recordId)
                                ->update(
                                    ['status' => $responseStatus,
                                     'content_length' => $responseContentLength,
                                     'body' => $responseBody,
                                    ]
                                );
        }
    }
}
