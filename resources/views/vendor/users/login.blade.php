@extends('core::public.master_login')

@section('title', trans('users::global.Log in'))

@section('page-header')
@endsection
@section('sidebar')
@endsection
@section('mainClass')
@endsection
@section('errors')
@endsection

@section('main')

<div id="login" class="form-signin">

    @include('core::admin._message', ['closable' => false])

    {!! BootForm::open() !!}
      <a href="/" class="welcome-logo"><img src="/img/logo.png" alt=""></a>
      <label for="login">Login</label>
      {!! Form::email('email')->addClass('form-control')->placeholder(trans('validation.attributes.email'))->autofocus(true)->autocomplete(false) !!}
      <label for="password">Password</label>
      {!! Form::password('password')->addClass('form-control')->placeholder(trans('validation.attributes.password')) !!}
      {!! BootForm::submit(trans('validation.attributes.log in'), 'btn-primary')->addClass('btn-lg btn-block') !!}
      <a id="help" href="{{ route('register') }}">Sign up</a>
      <div class="welcome-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. <a href="/r2/">Login FAQ</a></div>
    </form>

    {!! BootForm::close() !!}

</div>

@endsection
