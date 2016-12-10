@extends('core::public.master')
@section('title', trans('videos::global.name').' – Create')
@section('new_css', '/css/plyr.css')

@section('main')
       <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            {!! BootForm::open()->put()->action(route('admin::update-video', $model->id))->multipart()->role('form') !!}
              
            {!! BootForm::close() !!}

             <div class="edit-top">
              <a href="/" class="logo-edit"><img src="/img/logo-edit.png" alt=""></a>
              <a href="#" <?php if(empty($model->title)) echo 'style="display:none;"'; ?> class="title"><?=$model->title; ?></a>
              <div class="form-group edit-title">
                <input type="text" class="form-control" id="TitleVideo" value="" placeholder="Title">
              </div>
              <button type="button" class="save-video" id="saveBtn">Save</button>
            </div>

            <div class="col-md-12 video-bg">       
              <div class="video" id="video-wrapper">
                <video width="100%">
                  <source src="<?php echo '/uploads/videos/'.$file; ?>" type="video/mp4">
                </video>
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
                                  <div class="form-group uploadFile" id="drop_zone">
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
                </div>
            <!--<pre class="code">
              $("#bg-border").click(function() {
                $("input#uploadFile").click();
              });
            </pre>-->
              </div>
            </div>
          </div>

          <div class="timeline-slider">
              <div class="timeline-main" id="timeline-slider"></div>
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
                      <div class="upload-text" id="drop_zone_label"><span>Update a file</span><br> or drag it here.</div>
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
    <script src="/js/jquery.spritely.js"></script>
    <script src="/js/plyr.js"></script>
    <script src="/js/new_editor.js"></script>
    <script src="/js/jquery.easing.1.3.js"></script>
    <script>
      var controls = ["<div class='plyr__controls'>",
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
        
      window.path = '<?php echo '/uploads/videos/'.$file; ?>';
        
      Editor.slug = '<?=$slug; ?>';
      Editor.user_id = '<?=$user_id; ?>';
      Editor.type = 'create';
      Editor.init();
        
      });
      
      (function($) {
        $(document).ready(function() {

        var myVar;
        var isTagExist=false;

        function TimeOutFunction() {
         myVar = setInterval(checkTagFunc, 3000);
         console.log('setInterval myVar ',myVar);
        }

        function checkTagFunc() {
        
         console.log('checkTagFunc: isTagExist ',isTagExist);
         if (!isTagExist){
           if ($(".tag1").length){ 
                 $('.tag1')
                   .sprite({fps: 9, no_of_frames: 16})
                   .isDraggable()
                   .active();
                   isTagExist=true;
                   console.log('isTagExist ',isTagExist);
                   clearInterval(myVar);
           }
         }
        }
        
        TimeOutFunction();
        });
      })(jQuery);    
    </script>


@endsection
