@extends('core::public.master_oembed')

@section('title', $model->title.' – '.trans('videos::global.name').' – '.$websiteTitle)
@section('ogTitle', $model->title)
@section('bodyClass', 'body-videos body-video-'.$model->id.' body-page body-page-'.$page->id)
@section('new_css', '/css/plyr.css')

@section('main')
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
  <script src="/js/jquery.js"></script>
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
@endsection