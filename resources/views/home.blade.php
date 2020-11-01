@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <form method='POST' action="/home/join" style="font-size:large">
                    @csrf
                        <input type="text" placeholder="Enter Edu-Code" name="randomid">
                        <input type="submit" name="join" id="join" value="Join to Video">
                    </form>
                    <br><br><br>
                    <div style=></div>
                    Start your own unique EduTron session<br><br>
                    <a href="/home/join/{{ $randId }}" class="btn btn-primary">CREATE NEW</a>
                </div>

            </div>
        </div>
    </div>
    <center>
    <div>

        <br>
        <h4>Interact with 3D with your speech.</h4> 
        <br>
        <iframe id="frame" allow="microphone" height="500px" width="80%" src="https://bot.dialogflow.com/c1c9e43a-a423-4552-bcc0-dd6481229119"></iframe>
    </div>
    <dialog-flow-tag></dialog-flow-tag>

    </center>
</div>

@endsection