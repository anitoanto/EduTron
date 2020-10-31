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
</div>
@endsection