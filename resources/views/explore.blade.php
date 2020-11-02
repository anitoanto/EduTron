@extends('layouts.app')

@section('content')

<center>
    <div style="display: flex">

        <div style="width:50%">

            <br>
            <h4>Interact with 3D Model with your speech <br>via Natural Language Processing (NLP) model.</h4>
            <br>
            <iframe id="frame" allow="microphone" height="500px" width="80%" src="https://bot.dialogflow.com/c1c9e43a-a423-4552-bcc0-dd6481229119"></iframe>
        </div>
        <dialog-flow-tag></dialog-flow-tag>

    </div>
</center>
@endsection