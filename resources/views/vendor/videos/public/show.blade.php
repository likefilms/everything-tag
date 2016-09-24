@extends('core::public.master')

@section('title', $model->title.' – '.trans('videos::global.name').' – '.$websiteTitle)
@section('ogTitle', $model->title)
@section('description', $model->summary)
@section('image', $model->present()->thumbUrl())
@section('bodyClass', 'body-videos body-video-'.$model->id.' body-page body-page-'.$page->id)

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
                <video class="embed-responsive-item" id="video" preload data-src="<?php echo '/uploads/videos/'.$model->name; ?>" width="100%"></video>
                <div class="video-controls" id="video-controls">
                  <div class="timeline-slider">
                      <div class="timeline-main" id="timeline-slider"></div>
                  </div>

                  <a href="#" class="video-play" id="video-play"><span class="glyphicon glyphicon-play"></span></a>
                  
                  <div class="pull-left volume-block">
                    <a href="#" class="pull-left volume-icon"><span class="glyphicon glyphicon-volume-up" id="volume-icon"></span></a>
                    <div class="video-volume pull-left" id="video-volume"></div>
                  </div>


                  <span><span id="currentTime">0:00</span> / <span id="fullTime"></span></span>
                  <a href="#" class="pull-right" id="video-fullscreen" style="margin-left: 10px"><span class="glyphicon glyphicon-fullscreen"></span></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container video-social">
        <h1><?=$model->title; ?><sup>1,210 views</sup></h1>
        <div class="row tags-list">
          <?php if(!empty($tags)) { ?>
            <?php foreach($tags as $tag) { ?>
              <?php $start = decToTime($tag->start); ?>
              <div class="row tag"><span data-start="<?=$tag->start;?>" class="time"><?=$start;?></span><a href="<?=$tag->link;?>" target="_blank" class="name"><?=$tag->name;?></a></div>
            <?php } ?>
          <?php } else { ?>
            <div class="tags-empty-text">This video has no tag</div>
          <?php } ?>
        </div>
        <div class="row share">
          <div class="form-group">
            <label for="usr">Share:</label>
            <input type="text" class="form-control" value="http://everythingtag.com<?=$_SERVER['REQUEST_URI'];?>" id="like-url" />
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
    <script src="/js/editor.js"></script>
    <script src="/js/jquery.easing.1.3.js"></script>
    <script>
      
      $(function() {
        
        window.path = '<?php echo '/uploads/videos/'.$model->name; ?>';
        
        Editor.slug = '<?=$model->slug; ?>';
        Editor.type = 'show';
        Editor.init();
        
      });
      
    </script>


@endsection
