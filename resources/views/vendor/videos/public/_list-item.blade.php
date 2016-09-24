<!--<a href="{{ route($lang.'.videos.slug', $video->slug) }}" title="{{ $video->title }}">
    {!! $video->title !!}
    {!! $video->present()->thumb(null, 200) !!}
</a>-->

<tr>
  <td class="name"><a href="{{ route($lang.'.videos.edit', $video->slug) }}" title="{{ $video->title }}">
  <?php
    if(!empty($video->title)) {
      echo substr($video->title, 0, 30);
    } else {
      echo "no title";
    }
  ?>
  </a>
  </td>
  <td class="date"><?=date('d.m.Y',strtotime($video->updated_at));?></td>
  <td class="tags"><span><?=count(json_decode($video->labels));?></span></td>
  <td><span class="icon-share" aria-hidden="true"></span></td>
  <td><span class="icon-ok" aria-hidden="true"></span></td>
  <td><a class="delete-video" href="{{ route($lang.'.videos.delete', $video->slug) }}"><span  class="icon-remove" aria-hidden="true"></span></a></td>
</tr>

