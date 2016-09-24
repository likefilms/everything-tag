@extends('core::admin.master')

@section('title', trans('videos::global.New'))

@section('main')

    @include('core::admin._button-back', ['module' => 'videos'])
    <h1>
        @lang('videos::global.New')
    </h1>

    {!! BootForm::open()->action(route('admin::index-videos'))->multipart()->role('form') !!}
        @include('videos::admin._form')
    {!! BootForm::close() !!}

@endsection
