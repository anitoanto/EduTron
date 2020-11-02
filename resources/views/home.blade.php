@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header" style="font-size:22px">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <form method='POST' action="/home/join" style="font-size:large">
                        @csrf
                        <input style="font-size:22px" type="text" placeholder="Enter Edu-Code" name="randomid">
                        <input style="font-size:22px; padding:10px; width: 200px; height: 50px;" type="submit" name="join" id="join" value="Join to Video">
                    </form>
                    <br><br><br>
                    <div style="font-size:22px">
                        Start your own unique EduTron session<br><br></div>
                    <a style="font-size:22px; padding:10px; width: 200px; height: 50px;" href="/home/join/{{ $randId }}" class="btn btn-primary">CREATE NEW</a>
                </div>
                <a style="font-size:22px; padding:10px; width: 100%; height: 60px;" href="/home/explore" class="btn btn-primary">EXPLORE DOUBTS WITH VOICE</a>
            </div>
            <br>

        </div>
    </div>

</div>

@endsection