@extends('layouts.app')
@section('content')
<div style="text-align:center">
    Edu-Code: {{$randId}}</b>
</div>

<div>

    <video-chat randomid="{{ $randId }}" :user="{{ $user }}" 
    :others="{{ $others }}" pusher-key="{{ config('broadcasting.connections.pusher.key') }}" 
    pusher-cluster="{{ config('broadcasting.connections.pusher.options.cluster') }}"></video-chat>
</div>

@endsection