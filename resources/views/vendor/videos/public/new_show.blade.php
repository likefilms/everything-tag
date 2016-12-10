@extends('core::public.master')

@section('title', $model->title.' – '.trans('videos::global.name').' – '.$websiteTitle)
@section('ogTitle', $model->title)
@section('description', $model->description)
@section('keywords', $model->keywords)
@section('ogUpdated', $model->updated_at)
@section('image', $model->present()->thumbUrl())
@section('bodyClass', 'body-videos body-video-'.$model->id.' body-page body-page-'.$page->id)
@section('new_css', '/css/plyr.css')

@section('main')

    <!--@include('core::public._btn-prev-next', ['module' => 'Videos', 'model' => $model])
    <article>
        <h1>{{ $model->title }}</h1>
        {!! $model->present()->thumb(null, 200) !!}
        <p class="summary">{{ nl2br($model->summary) }}</p>
        <div class="body">{!! $model->present()->body !!}</div>
    </article>-->

      <?php
        $tags = json_decode($model->labels);

        function decToTime($dec) {
	      $decM = $dec / 60;
	      $decCeil = floor($decM);
	      $decS = ceil(($decM - $decCeil) * 60) . '';
	      
	      if(strlen($decS) == 1) $decS = '0' . $decS;
	      return $decCeil . ':' . $decS;
	    }
      ?>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
             <div class="col-md-12 video-bg">       
              <div class="video" id="video-wrapper">
                @if ($yt)
                  <div data-type="youtube" data-video-id="{{ $model->name }}"></div>
                @elseif ($vm)
                  <div data-type="vimeo" data-video-id="{{ $model->name }}"></div>
                @else
                  <video width="100%">
                    <source src="<?php echo '/uploads/videos/'.$model->name; ?>" type="video/mp4">
                  </video>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container video-social">
        <h1><?=$model->title; ?><sup>1,210 views</sup></h1>
        <div class="row tags-list">
          <?php if(!empty($tags)) { ?>
            <?php foreach($tags as $tag) { 
                if($tag->type == 'user') 
                  continue;
              ?>
              <?php $start = decToTime($tag->start); ?>
              <div class="row tag"><span data-start="<?=$tag->start;?>" data-end="<?=$tag->end;?>" class="time"><?=$start;?></span><a href="<?=$tag->link;?>" target="_blank" class="name"><?=$tag->name;?></a></div>
            <?php } ?>
          <?php } else { ?>
            <div class="tags-empty-text">This video has no tag</div>
          <?php } ?>
        </div>
        <div class="row share">
          <div class="form-group">
            <label for="usr">Share:</label>
            <input type="text" class="form-control" readonly value="http://everythingtag.com<?=$_SERVER['REQUEST_URI'];?>" id="like-url" />
            <script type="text/javascript">(function() {
            if (window.pluso)if (typeof window.pluso.start == "function") return;
            if (window.ifpluso==undefined) { window.ifpluso = 1;
              var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
              s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
              s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
              var h=d[g]('body')[0];
              h.appendChild(s);
            }})();</script>
            <div class="pluso" data-background="transparent" data-options="big,round,line,horizontal,nocounter,theme=04" data-services="facebook,google,twitter,vkontakte,odnoklassniki,email"></div>
          </div>
        </div>
        <a href="#" id="share"></a>
      </div>

    </div>
    <script src="/js/jquery.js"></script>
    <script src="/js/nouislider.js"></script>
    <script src="/js/dragresize.js"></script>
    <script src="/js/plyr.js"></script>
    @if ($yt)
      <script src="/js/yt_editor.js"></script>
    @else
      <script src="/js/new_editor.js"></script>
    @endif
    <script src="/js/jquery.easing.1.3.js"></script>
    <script>
      var controls = ["<div class='plyr__controls_top'><div class='controls_top_warp'>",
      "<button type='button' data-plyr='bookmark'>",
          "<svg><use xlink:href='/svg/plyr.svg#plyr-bookmark'></use></svg>",
          "<span class='plyr__sr-only'>Bookmark</span>",
          "<span class='bookmark_count'>0</span>",
      "</button>",
      "<button type='button' data-plyr='comments'>",
          "<svg><use xlink:href='/svg/plyr.svg#plyr-comments'></use></svg>",
          "<span class='plyr__sr-only'>Comments</span>",
          "<span class='comments_count'>2</span>",
      "</button>",
      "</div></div>",
      "<div class='plyr__controls'>",
      "<button type='button' data-plyr='play'>",
          "<svg><use xlink:href='/svg/plyr.svg#plyr-play'></use></svg>",
          "<span class='plyr__sr-only'>Play</span>",
      "</button>",
      "<button type='button' data-plyr='pause'>",
          "<svg><use xlink:href='/svg/plyr.svg#plyr-pause'></use></svg>",
          "<span class='plyr__sr-only'>Pause</span>",
      "</button>",
      "<span class='plyr__progress'>",
          "<label for='seek{id}' class='plyr__sr-only'>Seek</label>",
          "<input id='seek{id}' class='plyr__progress--seek' type='range' min='0' max='100' step='0.1' value='0' data-plyr='seek'>",
          "<progress class='plyr__progress--played' max='100' value='0' role='presentation'></progress>",
          "<progress class='plyr__progress--buffer' max='100' value='0'>",
              "<span>0</span>% buffered",
          "</progress>",
          "<span class='plyr__tooltip'>00:00</span>",
      "</span>",
      "<span class='plyr__time'>",
          "<span class='plyr__sr-only'>Current time</span>",
          "<span class='plyr__time--current'>00:00</span>",
      "</span>",
      "<button type='button' data-plyr='addtag'>",
          "<svg><use xlink:href='/svg/plyr.svg#plyr-addtag'></use></svg>",
          "<span class='plyr__sr-only'>Add tag</span>",
          "<input style='position: absolute;opacity: 0;width: 1px;height: 1px;' type='text' value='' id='copyTagLink'>",
      "</button>",
      "<button type='button' data-plyr='mute'>",
          "<svg class='icon--muted'><use xlink:href='/svg/plyr.svg#plyr-muted'></use></svg>",
          "<svg><use xlink:href='/svg/plyr.svg#plyr-volume'></use></svg>",
          "<span class='plyr__sr-only'>Toggle Mute</span>",
      "</button>",
      "<span class='plyr__volume'>",
          "<label for='volume{id}' class='plyr__sr-only'>Volume</label>",
          "<input id='volume{id}' class='plyr__volume--input' type='range' min='0' max='10' value='5' data-plyr='volume'>",
          "<progress class='plyr__volume--display' max='10' value='0' role='presentation'></progress>",
      "</span>",
      "<button type='button' data-plyr='fullscreen'>",
          "<svg class='icon--exit-fullscreen'><use xlink:href='/svg/plyr.svg#plyr-exit-fullscreen'></use></svg>",
          "<svg><use xlink:href='/svg/plyr.svg#plyr-enter-fullscreen'></use></svg>",
          "<span class='plyr__sr-only'>Toggle Fullscreen</span>",
      "</button>",
      "</div>",
      "<div class='plyr__controls_bookmark'>",
        "<h3><span>∞</span>Tags in this video</h3>",
        "<div class='row'>",
          "<div class='control-scroll'><div class='scrollbar'><div class='track'><div class='thumb'><div class='end'></div></div></div></div>",
            "<div class='viewport'>",
              "<div class='col-md-1'></div><div class='col-md-5'>",
                "<ul class='tags-tab'>",
                  "<li class='active' data-id='saved'>Saved by you</li>",
                  "<li data-id='added'>Added by you</li>",
                "</ul>",
                "<div id='saved-tag' class='bookmark-tags-list'><span>Save at least one videomark</span>",
                "</div>",
                "<div id='added-tag' class='bookmark-tags-list'><span>You haven't added any videomarks</span>",
                "</div>",
                "<h4 class='remained'>Remained</h4>",
                "<div class='remained-tags-list'>",
                "</div>",
                "<div class='share_tag'>",
                  "<div class='close'>x</div>",
                  "<div class='title'>Your personal tag</div>",
                  "<div class='unique-link'><a href='#'>Unique link name</a></div>",
                  "<div class='form-group'><input type='text' class='form-control' id='tagLink' placeholder='Link' value='https://everythingtag.com/en/video/396l1jfo' readonly='readonly'></div>",
                  "<div class='form-group'><input type='text' class='form-control' id='tagComment' placeholder='Comment'></div>",
                  "<div class='pluso' data-background='transparent' data-options='medium,round,line,horizontal,nocounter,theme=04' data-services='facebook,google,twitter,vkontakte,odnoklassniki,email' data-title='My tag' data-url='https://everythingtag.com/en/video/396l1jfo'></div>",
                  "<span class='copyTagLink'>Copy link</span>",
                "</div>",
              "</div><div class='col-md-1'></div>",
              "<div class='col-md-5 related'>",
                "<h4>Related</h4>",
                "<div class='tag-link'><a href='#'><span class='name'>∞bluedress</span><sapn class='exactly'>197</sapn><img src='/img/tagimg1.png' alt=''></a></div>",
                "<div class='tag-link'><a href='#'><span class='name'>∞bluedress</span><sapn class='exactly'>197</sapn><img src='/img/tagimg2.png' alt=''></a></div>",
              "</div>",
             "</div>",
          "</div>",
        "</div>",
        "<button type='button' data-plyr='back'>",
          "<svg><use xlink:href='/svg/plyr.svg#plyr-back'></use></svg>",
          "<span class='plyr__sr-only'>Back</span>",
      "</button>",
      "</div>",
      "<div class='plyr__tag_save'>Tag save and link copied</div>"].join("");

      var instances = plyr.setup({
        'debug': true,
        'controls': ['play', 'progress', 'current-time', 'addtag', 'mute', 'volume', 'fullscreen'],
        'html': controls,
        'iconUrl': '/svg/plyr.svg',
        'events': ["ready", "ended", "progress", "stalled", "playing", "waiting", "canplay", "canplaythrough", "loadstart",
        "loadeddata", "loadedmetadata", "timeupdate", "volumechange", "play", "pause", "error", "seeking", "emptied","addtag"],
        "clickToPlay": false
      });

      instances[0].on('ready', function(event) {
        
        window.path = '<?php echo '/uploads/videos/'.$model->name; ?>';
          
        Editor.slug = '<?=$model->slug; ?>';
        Editor.type = 'show';
        Editor.id = '<?=$model->id; ?>';
        Editor.user_id = '<?=$model->user_id; ?>';
        Editor.init();
      }); 
      
    </script>

    @if(isset($model_tag))
      <script>
        var type_page = '<?=$type_page; ?>';

        if(type_page == 'tag') {
          instances[0].getMedia().currentTime = '<?=$model_tag->start; ?>';
        }
      </script>
    @endif


@endsection
