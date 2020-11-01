<?php

namespace App\Http\Controllers;

use App\Events\DialogFlowWebhook;
use Dialogflow\WebhookClient;

use Illuminate\Http\Request;
use App\Events\ShareRotation;

class DialogFlowController extends Controller
{
    public function notifyWithLabel($request)
    {
        $agent = WebhookClient::fromData($request->json()->all());
        $intent = $agent->getIntent();
        event(new DialogFlowWebhook($intent));
    }
}
