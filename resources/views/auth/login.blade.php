@extends('layouts.app')
@section('content')

<div class="login-page-basic">

  <form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="imgcontainer">
      <img
        src="https://png.pngtree.com/png-vector/20190411/ourmid/pngtree-business-male-icon-vector-png-image_916468.jpg"
        alt="Avatar" class="avatar">
    </div>
    <center>
      <div class="container">
        <label for="email"><b>Email</b></label><br>
        <input type="email" placeholder="Enter Email ID" name="email" required autofocus><br><br>
        <label for="password"><b>Password</b></label><br>

        <input type="password" placeholder="Enter Password" name="password" required><br>

        <button type="submit">LOGIN</button><br>

        <a href="/home/login" class="btn btn-primary " style="width:220px">GOTO REGISTRATION</a>
      </div>
    </center>

  </form>
</div>
@endsection