@extends('pages::public.master')

@section('page')
<div class="row">
  <div class="panel panel-default">
      <div class="panel-body">
        <a href="#" class="welcome-logo"><img src="img/logo.png" alt=""></a>
        <form action="" id="upload_form" method="post" enctype="multipart/form-data">
          <!--
          <div class="form-group">
            <label for="videoID">Link for upload</label>
            <input id="videoID" value="https://www.youtube.com/watch?v=6Ejga4kJUts" type="text" name="videoID" onKeyUp="getVideo(this.value)" placeholder="Youtube Link">
          </div>
          <div class="form-group or">or</div>
          -->
          <div class="form-group uploadFile" id="drop_zone">
            <div id="bg-border"></div>
            <input type="file" name="name" id="uploadFile">
            <div class="upload-text"><span>Choose a file</span> or drag it here.</div>
          </div>
          <div id="video_info" style="margin-top:10px;margin-bottom:10px;"></div>

            <input type="submit" class="btn btn-default" value="Upload"/>
            <img src="img/loading.svg" class="loading-video-file" alt="loading file">
        </form>

        <div class="welcome-text">{!! $page->present()->body !!}</div>
      </div>
  </div>
</div>
@endsection