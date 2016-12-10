@section('js')
    <script src="{{ asset('components/ckeditor/ckeditor.js') }}"></script>
@endsection

@include('core::admin._buttons-form')

{!! BootForm::hidden('id') !!}

@if (isset($user_id))
	{!! BootForm::hidden('user_id')->value($user_id) !!}
@else
	{!! BootForm::hidden('user_id') !!}
@endif

@include('core::admin._image-fieldset', ['field' => 'image'])

{!! TranslatableBootForm::hidden('status')->value(0) !!}

{!! TranslatableBootForm::checkbox(trans('validation.attributes.online'), 'status') !!}

{!! TranslatableBootForm::text('Title', 'title')->placeholder('Title') !!}

@if (isset($slug))
	{!! BootForm::hidden('slug', 'slug')->value($slug) !!}
@else
	{!! BootForm::hidden('slug', 'slug') !!}
@endif

{!! BootForm::text('Video', 'video_id')->placeholder('Video') !!}

{!! BootForm::text('Start', 'start')->placeholder('Start') !!}
{!! BootForm::text('End', 'end')->placeholder('End') !!}
{!! BootForm::text('Width', 'width')->placeholder('width') !!}
{!! BootForm::text('Height', 'height')->placeholder('height') !!}
{!! BootForm::text('Top', 'top')->placeholder('top') !!}
{!! BootForm::text('Left', 'left')->placeholder('left') !!}

{!! BootForm::text('Link', 'link')->placeholder('Link') !!}

