@extends('pages::public.master')

@section('bodyClass', 'body-tags body-tags-index body-page body-page-'.$page->id)

@section('main')

    {!! $page->present()->body !!}

    @include('galleries::public._galleries', ['model' => $page])

    @if ($models->count())
    @include('tags::public._list', ['items' => $models])
    @endif

@endsection
