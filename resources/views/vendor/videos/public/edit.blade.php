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

       <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="edit-top">
              <a href="/" class="logo-edit"><img src="/img/logo-edit.png" alt=""></a>
              <a href="#" <?php if(empty($model->title)) echo 'style="display:none;"'; ?> class="title"><?=$model->title; ?></a>
              <div <?php if(!empty($model->title)) echo 'style="display:none;"'; ?> class="form-group edit-title">
                <input type="text" class="form-control" id="TitleVideo" value="<?=$model->title; ?>" placeholder="Title">
              </div>
              <button type="button" class="save-video" id="saveBtn">Save</button>
              <a href="{{ route($lang.'.videos.slug', $model->slug) }}" target="_blank" class="prew-video"></a>
            </div>

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
                <div class="control-tags">
                  <div class="panel panel-default add-label">
                    <div class="panel-heading">Add new tag</div>
                    <div class="panel-body">
                      <ul class="type-tag">
                        <li class="active"><a class="static" href="#">static</a></li>
                        <li><a class="animated" href="#">animated</a></li>
                      </ul>
                      <form id="static">
                      <div class="control-scroll">
                        <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
                          <div class="viewport">
                               <div class="overview">
                                <div class="form-group">
                                  <input type="text" class="form-control" id="labelName" placeholder="Name">
                                </div>
                                <div class="form-group">
                                  <input type="text" class="form-control" id="labelLink" placeholder="Link">
                                </div>
                                <div class="form-group uploadFile">
                                  <div id="bg-border"></div>
                                  <input type="file" class="file" id="labelImg">
                                  <div class="upload-text"><span>Choose a file</span><br> or drag it here.</div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="labelAdd">Add</button>
                      </form>
                      <form id="animated">
                        <div class="control-scroll" >
                        <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
                          <div class="viewport">
                               <div class="overview">
                                <div class="form-group">
                                  <input type="text" class="form-control" id="labelName2" placeholder="Name">
                                </div>
                                <div class="form-group">
                                  <input type="text" class="form-control" id="labelLink2" placeholder="Link">
                                </div>
                                <div class="prepared-variations">
                                  <div class="title">prepared variations</div>
                                  <div class="variations">
                                    <!--<div class="variant" data-svg="svg/itag_1.svg"><img src="svg/itag_1.svg?color=#ffffff" width="72" height="72" alt="Tag 1"></div>-->
                                    <div class="variant" data-svg="/svg/itag_01.svg"><img src="/img/itag_01.png" alt="Tag 1"></div>
                                    <div class="variant" data-svg="/svg/itag_02.svg"><img src="/img/itag_02.png" alt="Tag 2"></div>
                                    <div class="variant" data-svg="/svg/itag_03.svg"><img src="/img/itag_03.png" alt="Tag 3"></div>
                                    <div class="variant" data-svg="/svg/itag_04.svg"><img src="/img/itag_04.png" alt="Tag 4"></div>
                                    <div class="variant" data-svg="/svg/itag_05.svg"><img src="/img/itag_05.png" alt="Tag 5"></div>
                                    <div class="variant" data-svg="/svg/itag_06.svg"><img src="/img/itag_06.png" alt="Tag 6"></div>
                                    <div class="variant" data-svg="/svg/itag_07.svg"><img src="/img/itag_07.png" alt="Tag 7"></div>
                                    <div class="variant" data-svg="/svg/itag_08.svg"><img src="/img/itag_08.png" alt="Tag 8"></div>
                                  </div>
                                </div>
                                <input type="file" style="display: none;" class="file" id="labelImg2">
                              </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="labelAdd2">Add</button>
                        <!--<button type="button" class="btn btn-success btn-lg btn-block" id="saveBtn">Save</button>-->
                      </form>
                      
                    </div>
                  </div>
                </div>
                <div id="add-tag"></div>
            <!--<pre class="code">
              $("#bg-border").click(function() {
                $("input#uploadFile").click();
              });
            </pre>-->
              </div>
            </div>
          </div>
          <div class="timeline-edit-tag">
            <div class="timeline" id="timeline">
              <div class="row timeline-labels">
                <div class="col-md-12" id="timeline-labels"></div>
              </div>
            </div>
            <div class="edit-tags" id="edit-tags">
              <div class="panel panel-default edit-label">
                <div class="panel-body">
                  <form>
                    <div class="form-group">
                      <input type="text" class="form-control" id="labelNameE" placeholder="Name">
                      <input type="text" class="form-control" id="labelLinkE" placeholder="Link">
                    </div>
                    <div class="form-group uploadFile">
                      <input type="file" class="file" id="labelImgE">
                      <div class="upload-text"><span>Update a file</span><br> or drag it here.</div>
                    </div>
                    <div class="form-group">
                      <button type="button" class="btn btn-primary" id="labelAddE">UPDATE</button>
                      <button type="button" class="btn btn-primary" id="labelDelete">DELETE</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!--
          <div class="visible-sm visible-xs">
            <div class="panel panel-default add-label">
              <div class="panel-heading">Добавить ярлык</div>
              <div class="panel-body">
                <form>
                  <div class="form-group">
                    <input type="text" class="form-control" id="labelName2" placeholder="Название ярлыка...">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" id="labelLink2" placeholder="Ссылка...">
                  </div>
                  <div class="form-group text-center">
                    <input type="file" class="file" id="labelImg2">
                  </div>
                  <button type="button" class="btn btn-primary pull-right" id="labelAdd2">Добавить</button>
                </form>
              </div>
            </div>
            <div class="panel panel-default edit-label" style="display:none">
              <div class="panel-heading">Редактировать ярлык</div>
              <div class="panel-body">
                <form>
                  <div class="form-group">
                    <input type="text" class="form-control" id="labelNameE2" placeholder="Название ярлыка...">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" id="labelLinkE2" placeholder="Ссылка...">
                  </div>
                  <button type="button" class="btn btn-primary pull-right" id="labelAddE2">Сохранить</button>
                </form>
              </div>
            </div>
            <button type="button" class="btn btn-success btn-lg btn-block" id="saveBtn2">Сохранить видео</button>
            <div class="progress" style="display:none">
              <div class="progress-bar progress-bar-striped active" style="width: 0%">
                <span>0%</span>
              </div>
            </div>
          </div>
          -->
        </div>
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
        Editor.type = 'editor';
        Editor.init();
        

        
      });
      
    </script>


@endsection
