@extends('core::public.master_login')

@section('title', trans('users::global.Register'))

@section('page-header')
@endsection
@section('sidebar')
@endsection
@section('mainClass')
@endsection
@section('errors')
@endsection

@section('main')

<div id="register" class="form-signin">

    @include('core::admin._message', ['closable' => false])

    {!! BootForm::open() !!}
        <a href="/" class="welcome-logo"><img src="/img/logo.png" alt=""></a>

        {!! BootForm::email(trans('validation.attributes.email'), 'email')->addClass('input-lg') !!}
        {!! BootForm::text(trans('validation.attributes.first_name'), 'first_name')->addClass('input-lg') !!}
        {!! BootForm::text(trans('validation.attributes.last_name'), 'last_name')->addClass('input-lg') !!}
        {!! BootForm::password(trans('validation.attributes.password'), 'password')->addClass('input-lg') !!}
        {!! BootForm::password(trans('validation.attributes.password_confirmation'), 'password_confirmation')->addClass('input-lg') !!}

        <div class="form-group form-action">
            {!! BootForm::submit(trans('validation.attributes.register'), 'btn-primary')->addClass('btn-lg btn-block')->onclick("ga('send','event','register','submit');") !!}
        </div>

        <a id="help" href="{{ route('login') }}">Log in</a>

    {!! BootForm::close() !!}

</div>

@endsection
