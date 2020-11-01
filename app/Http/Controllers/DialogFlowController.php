<?php

namespace App\Http\Controllers;

use App\Events\DialogFlowWebhook;
use Dialogflow\WebhookClient;

use Illuminate\Http\Request;
use App\Events\ShareRotation;

class DialogFlowController extends Controller
{
    public function notifyWithLabel(Request $request)
    {
        $agent = WebhookClient::fromData($request->json()->all());
        $intent = $agent->getIntent();
        event(new DialogFlowWebhook($intent));

        $raw = json_decode($request, true);
        $chat = $raw['queryText']['fulfillmentText'];

        $res = '{
  "fulfillmentMessages": [
    {
      "text": {
        "text": [
          "'.$chat.'"
        ]
      }
    }
  ]
}';

        return response('', 200)->header('Content-Type', 'text/plain');

        // return response()->json([
        //     'fulfillmentMessages' => [
        //         'text' => [
        //             'text' => "Text response from webhook"
        //         ]
        //     ]
        // ]);
    }
}
