
@extends('layouts.app')
@section('content')

<div id="login-box">
    <div class="left-box">

        <h1> Register </h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input type="text" name="name" placeholder="Name" autofocus/>
            <input type="email" name="email" placeholder="Email" />
            <input type="password" name="password" placeholder="Password" />
            <input type="password" name="password_confirmation" placeholder="Confirm Password" />
            <input type="submit" name="signup-button" value="Create" />
        </form>
    </div>
    <div class="log-in">Do you have an account ? <br><a href="/login" class="btn btn-primary " style="width:170px"> LOGIN</a></div>
</div>

@endsection