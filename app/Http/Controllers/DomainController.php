<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Jobs\CollectAdditionalData;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DomainController extends BaseController
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return view('main', ['errors' => $validator->errors()]);
        }

        $urlFromInput = $request->input('url');
        
        try {
            $domain = Domain::whereName($urlFromInput)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $domain = Domain::create(['name' => $urlFromInput]);
            dispatch(new CollectAdditionalData($urlFromInput, $domain->id));
        }
        
        return redirect(route('domains.show', ['id' => $domain->id]));
    }

    public function show(Request $request, $id)
    {
        $domainViewMap = [
            'init' => function ($domain) {
                return view('main', ['message' => 'Please wait for results']);
            },
            'failed' => function ($domain) {
                return view('domain_fail', ['domain' => $domain]);
            },
            'completed' => function ($domain) {
                return view('domain', ['domain' => $domain]);
            }
        ];
        
        $domain = Domain::findOrFail($id);
        
        return $domainViewMap[$domain->state]($domain);
    }

    public function index(Request $request)
    {
        $domains = Domain::paginate(5);
        return view('domains', ['domains' => $domains]);
    }
}
