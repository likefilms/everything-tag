@extends('core::public.master_oembed')

@section('title', $model->title.' – '.trans('videos::global.name').' – '.$websiteTitle)
@section('ogTitle', $model->title)
@section('bodyClass', 'body-videos body-video-'.$model->id.' body-page body-page-'.$page->id)

@section('main')
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
  <script src="/js/jquery.js"></script>
  <script src="/js/nouislider.js"></script>
  <script src="/js/dragresize.js"></script>
  <script src="/js/editor.js"></script>
    <script>
      
      $(function() {
        
        window.path = '<?php echo '/uploads/videos/'.$model->name; ?>';
        
        Editor.slug = '<?=$model->slug; ?>';
        Editor.type = 'show';
        Editor.init();
        
      });
      
    </script>
@endsection