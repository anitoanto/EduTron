<?php

namespace App\Http\Controllers;

use App\Events\DialogFlowWebhook;
use Dialogflow\WebhookClient;

use Illuminate\Http\Request;
use App\Events\ShareRotation;

class DialogFlowController extends Controller
{
    public function notifyWithLabel()
    {
        // $agent = WebhookClient::fromData($request->json()->all());
        // $intent = $agent->getIntent();
        // event(new DialogFlowWebhook($intent));

        return response('{
  "fulfillmentMessages": [
    {
      "text": {
        "text": [
          "Text response from webhook"
        ]
      }
    }
  ]
}', 200)
            ->header('Content-Type', 'text/plain');

        // return response()->json([
        //     'fulfillmentMessages' => [
        //         'text' => [
        //             'text' => "Text response from webhook"
        //         ]
        //     ]
        // ]);
    }
}
