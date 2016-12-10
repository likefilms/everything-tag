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
@include('core::admin._file-fieldset', ['field' => 'name'])

{!! TranslatableBootForm::text('Title', 'title')->placeholder('Title') !!}

@if (isset($slug))
	{!! BootForm::hidden('slug', 'slug')->value($slug) !!}
@else
	{!! BootForm::hidden('slug', 'slug') !!}
@endif


{!! TranslatableBootForm::hidden('status')->value(0) !!}


{!! TranslatableBootForm::checkbox(trans('validation.attributes.online'), 'status') !!}
{!! TranslatableBootForm::textarea(trans('validation.attributes.description'), 'description')->rows(2) !!}
{!! TranslatableBootForm::text('Keywords', 'keywords')->placeholder('keywords') !!}
{!! TranslatableBootForm::textarea(trans('validation.attributes.labels'), 'labels')->rows(4) !!}
{!! TranslatableBootForm::textarea(trans('validation.attributes.body'), 'body')->addClass('ckeditor') !!}
