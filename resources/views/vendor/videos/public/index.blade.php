@extends('pages::public.master')

@section('bodyClass', 'body-videos body-videos-index body-page body-page-'.$page->id)

@section('main')

    {!! $page->present()->body !!}

    @include('galleries::public._galleries', ['model' => $page])

    @if ($models->count())
   		@include('videos::public._list', ['items' => $models])
    @else 
		<div class="row list">
		  <div class="panel panel-default">
		    <div class="panel-body">
		    <a href="#" class="welcome-logo"><img src="/img/logo.png" alt=""></a>
			<h2>You do not have any videos.</h2>
		    </div>
		  </div>
		</div>      
    @endif

@endsection
