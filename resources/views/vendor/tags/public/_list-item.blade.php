<li>
    <a href="{{ route($lang.'.tags.slug', $tag->slug) }}" title="{{ $tag->title }}">
        {!! $tag->title !!}
        {!! $tag->present()->thumb(null, 200) !!}
    </a>
</li>
