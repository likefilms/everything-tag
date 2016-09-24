@extends('pages::public.master')

@section('page')

	<a href="/" class="logo-text"><img src="/img/logo_cont_text.png" alt=""></a>
	<div class="row">
		<div class="col-md-2"></div>
	  	
	  	<div class="col-md-10">
	    @if($children)
	    <ul class="nav nav-subpages">
	        @foreach ($children as $child)
	        @include('pages::public._listItem', array('child' => $child))
	        @endforeach
	    </ul>
	    @endif
		
	    {!! $page->present()->body !!}
	    @include('galleries::public._galleries', ['model' => $page])
	</div>
@endsection
