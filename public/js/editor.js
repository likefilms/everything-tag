var Editor = new function() {
  
  (function() {
    var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
                                window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
    window.requestAnimationFrame = requestAnimationFrame;
  })();
  
  var _this = this,
      type,
      duration,
      $timelineSlider,
      currentTime = 0,
      $currentTime = $('#currentTime'),
      labels = {},
      slug,
      id,
      dragresize;
  
  _this.labels = labels;
  
  var Label = function(id, name, link, data, svg, width, height, left, top, startI, endI) {
    
    var self = this;
    
    this.id = id;
    this.name = name;
    this.link = link;
    this.is_svg = '';

    this.data = data;

    if(this.data.indexOf("data:image/svg+xml;") != -1) this.is_svg = ' is_svg';

    this.start = 0;
    this.end = duration;
    
    if(width) {
      
      this.start = startI;
      this.end = endI;
      
    }

    if(_this.type=='editor' || _this.type=='create') {
      var class_label = "drsElement drsMoveHandle";
    } else {
      var class_label = "";
    }

    if(_this.type=='editor' || _this.type=='create') {
      var labelVideo = $('<div class="video-label {class}" id="{id}"><img src="{data}"></div>'.replace(/{data}/g, this.data).replace(/{class}/g, class_label).replace(/{id}/g, "video-label" + this.id));
    } else {
      var labelVideo = $('<div class="video-label publish_link {class}"><a target="_blank" onclick="{ga}" href="{link}"><img src="{data}"></a></div>'.replace(/{data}/g, this.data).replace(/{class}/g, class_label).replace(/{link}/g, this.link).replace(/{ga}/g,"ga('send','event','tags','click','Name tag: " + this.name + "');"));
    }

    var labelImg = labelVideo.find('img');
    
    if(width) {
      
      var coofW = _this.$video.width() / _this.$video[0].videoWidth,
          coofH = _this.$video.height() / _this.$video[0].videoHeight;
      
      labelVideo.css('width', width * coofW + 'px').css('height', height * coofH + 'px').css('left', left * coofW + 'px').css('top', top * coofH + 'px');;
      
    }
    else {
      
      labelVideo.css('width', labelImg[0].naturalWidth).css('height', labelImg[0].naturalHeight);
      
      if(labelImg[0].naturalWidth > _this.$video.width() / 2) labelVideo.css('width', _this.$video.width() / 2);
      if(labelImg[0].naturalHeight > _this.$video.height() / 2) {
        
        var oldH = labelVideo.height();
        labelVideo.css('height', _this.$video.height() / 2);
        labelVideo.css('width', labelImg[0].naturalWidth * _this.$video.height() / 2 / oldH);
        
      }
      
      labelVideo.css('left', (_this.$video.width() - labelVideo.width()) / 2 + 'px').css('top', (_this.$video.height() - labelVideo.height()) / 2 + 'px');
      
    }
    
    labelVideo.appendTo('#video-wrapper');
    
    this.elem = labelVideo;
    
    var labelBlock = $('<div class="row lbl{is_svg}" id="label{id}" data-id="{id}"><div class="col-md-12"><div class="timeline-label" id="slider{id}"></div><a href="#" style="display:none;" class="text-danger pull-right"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a><a href="#"  class="label-name{is_svg}" title="{link}" data-id="{id}">{name}</a></div></div>'.replace(/{id}/g, this.id).replace(/{name}/g, this.name).replace(/{link}/g, this.link).replace(/{is_svg}/g, this.is_svg));
    
    labelBlock.find('.label-name').on('click', function(e) {
      
      e.preventDefault();
      
      var editBlock = $('.edit-label');

      /*if($(this).hasClass("is_svg")) {
        $('.edit-tags').find(".form-group:nth-child(1)").addClass("svg");
        $('.edit-tags').find(".uploadFile").hide();
      } else {
        $('.edit-tags').find(".form-group:nth-child(1)").removeClass("svg");
        $('.edit-tags').find(".uploadFile").show();
      }*/

      
      //$('.add-label').hide();
      $('.edit-tags').show();
      editBlock.find('#labelNameE').val(labels[$(this).attr('data-id')].name);
      editBlock.find('#labelNameE2').val(labels[$(this).attr('data-id')].name);
      editBlock.find('#labelLinkE').val(labels[$(this).attr('data-id')].link);
      editBlock.find('#labelLinkE2').val(labels[$(this).attr('data-id')].link);
      
      editBlock.find('#labelAddE').val($(this).attr('data-id'));
      editBlock.find('#labelDelete').val($(this).attr('data-id'));

      editBlock.find('#labelAddE2').val($(this).attr('data-id'));
      editBlock.find('#labelDelete2').val($(this).attr('data-id'));
      
    });
    
    labelBlock.find('.glyphicon-remove').on('click', function(e) {
      
      e.preventDefault();
      
      if(confirm('Вы действительно хотите удалить метку?')) {
        
        labelBlock.remove();
        labelVideo.remove();
        
        delete(labels[self.id]);
        
      }
      
    });
    
    var tooltipSlider = labelBlock.find('.timeline-label');
    
    noUiSlider.create(tooltipSlider[0], {
      start: [this.start, this.end],
      connect: true,
      behaviour: 'drag',
      margin: 1,
      range: {
        'min': 0,
        'max': duration
      }
    });

    var tipHandles = tooltipSlider[0].getElementsByClassName('noUi-handle'),
        tooltips = [];
    
    for (var i = 0; i < tipHandles.length; i++){
      tooltips[i] = document.createElement('div');
      tipHandles[i].appendChild(tooltips[i]);
    }
    
    tooltips[0].className += 'tooltip-left out';
    tooltips[1].className += 'tooltip-right out';
    
    var leftElem = tooltipSlider.find('.noUi-connect'),
        leftTooltip = tooltipSlider.find('.tooltip-left'),
        rightElem = tooltipSlider.find('.noUi-origin.noUi-background'),
        rightTooltip = tooltipSlider.find('.tooltip-right');
    
    tooltipSlider[0].noUiSlider.on('update', function(values, handle) {
      
      tooltips[handle].innerHTML = decToTime(values[handle]);
      if(handle == 0) self.start = values[handle];
      else self.end = values[handle];
      
      if(leftElem.position().left < 50) leftTooltip.removeClass('out');
      else if(!leftTooltip.hasClass('out')) leftTooltip.addClass('out');

      if(rightElem.width() < 50) rightTooltip.removeClass('out');
      else if(!rightTooltip.hasClass('out')) rightTooltip.addClass('out');
      
      update();

    });
    
    if(_this.type=="editor" || _this.type=='create') labelBlock.appendTo('#timeline');
    
  };
  
  var getId = function() {
    
    return String(Math.random()).substring(2);
    
  };
  
  var decToTime = function(dec) {
    
    var decM = dec / 60,
        decCeil = Math.floor(decM),
        decS = Math.ceil((decM - decCeil) * 60) + '';
    
    if(decS.length == 1) decS = '0' + decS;
    
    return decCeil + ':' + decS;
    
  };
  
  var update = function() {
    
    currentTime = _this.$video[0].currentTime;
    
    $currentTime.text(decToTime(currentTime));
      
    $timelineSlider[0].noUiSlider.set(_this.$video[0].currentTime);
    
    for(var l in labels) {
      
      l = labels[l];
      
      if(currentTime >= l.start && currentTime <= l.end) {
        l.elem.show();

        // Отправляем аналитику
        if(_this.type!="editor" || _this.type!="create") ga('send','event','tags','show','Name tag: ' + l.name);

        if(l.elem.find("img").attr("src")=="") {
          l.elem.find("img").attr("src",l.data);
        }
      } else {
        l.elem.hide();
        l.elem.find("img").attr("src","");
      }
      
    }
    
   requestAnimationFrame(update);
    
  };
  
  var getVars = new function() { 
     var $_GET = {}; 
     var __GET = window.location.search.substring(1).split("&"); 
     for(var i=0; i<__GET.length; i++) { 
        var getVar = __GET[i].split("="); 
        $_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1]; 
     } 
     return $_GET; 
  }
  
  var initInterface = function() {
    
    // Save editable label
    $('#labelAddE, #labelAddE2').on('click', function() {
      
      if($(this).attr('id') == 'labelAddE') {
        
        var name = $('#labelNameE').val();
        var link = $('#labelLinkE').val();
        var file = $('#labelImgE')[0].files[0];
        
      }
      else {
        
        var name = $('#labelNameE2').val();
        var link = $('#labelLinkE2').val();
        
      }
      
      var id = $(this).val();
      
      var editBlock = $('.edit-label');

      if(file && /^image\//.test(file.type)) {
        var reader = new FileReader();
        reader.onload = function(e) {
          
          labels[id].data = e.target.result;
          $('#video-label' + id).find('img').attr('src', e.target.result);

        };
        reader.readAsDataURL(file);
      }
      
      labels[id].name = name;
      labels[id].link = link;
      
      $('#label' + id).find('.label-name').attr('title', link).text(name);
      
      //$('.add-label').show();
      $('.edit-tags').hide();
      
      editBlock.find('#labelNameE').val('');
      editBlock.find('#labelNameE2').val('');
      editBlock.find('#labelLinkE').val('');
      editBlock.find('#labelLinkE2').val('');
      
    });

    $('#labelDelete, #labelDeleteE2').on('click', function() {
       var id = $(this).val();
       $("#label" + id).find(".glyphicon-remove").click();
       $('.edit-tags').hide();
    });
    
    // Save video
    var saveVideo = function() {
      
      if(!$.isEmptyObject(labels) && !$(this).hasClass('disabled')) {
        
        var data_labels = [],
            i = 0,
            coofW = _this.$video[0].videoWidth / _this.$video.width(),
            coofH = _this.$video[0].videoHeight / _this.$video.height();
        
        for(var label in labels) {
          
          var left = labels[label].elem.css('left'),
              top = labels[label].elem.css('top');
          
          data_labels[i] = {
            
            name: labels[label].name,
            link: labels[label].link,
            start: labels[label].start,
            end: labels[label].end,
            data: labels[label].data,
            left: Math.round(coofW * left.substr(0, left.length - 2)),
            top: Math.round(coofH * top.substr(0, top.length - 2)),
            width: Math.round(coofW * labels[label].elem.width()),
            height: Math.round(coofH * labels[label].elem.height())
            
          };
          
          i++;
          
        }

        var data = {
          token: $("input[name=_token]").val(),
          method: "PUT",
          user_id: _this.user_id,
        };

        if(_this.id) {
          data.id = _this.id;
          var method = "update";
        } else {
          data.name = window.path.split("/uploads/videos/")[1];
          data.slug = _this.slug;
          var method = "create";
        }

        data.en = {
          title: $("#TitleVideo").val(),
          status: 1,
          body: "",
          labels: JSON.stringify(data_labels)
        }
        
        var self = $(this);
        self.addClass('disabled').text('Видео сохраняется...');
        $('.progress').show().find('div').css('width', '0%').find('span').text('0%');
        
        
        $.ajax({
          type: 'post',
          url: '/api/videos/' + method + '/' + _this.slug,
          data: data,
          success: function(data) {

            window.location.href = "/en/video/" + _this.slug + "/edit";

            /*var timeout = setInterval(function() {
              
              $.ajax({
                type: 'post',
                url: './index.php',
                data: { progress: path.replace(/^.*[\\\/]/, '') },
                success: function(data) {
                  
                  $('.progress').find('div').css('width', data + '%').find('span').text(data + '%');
                  
                }
              });
              
            }, 1000);

            clearInterval(timeout);

            $('.progress').find('div').css('width', '100%').find('span').text('100%');

            setTimeout(function() {
              self.removeClass('disabled').text('Save');
              $('.progress').hide().find('div').css('width', '0%').find('span').text('0%');
              location.href = "index.php?view=editor&id="+ data;
            }, 1000);
            */
          }
        });
        
      }
      else {
        
        if(!$(this).hasClass('disabled')) alert('Добавьте хотя бы одну метку.');
        
      }
      
    };
    
    $('#saveBtn').on('click', saveVideo);
    $('#saveBtn2').on('click', saveVideo);
    
    // Get video duration and fill timeline labels
    duration = _this.$video[0].duration;

    $('#fullTime').text(decToTime(duration));
    
    var duration12 = duration / 11;
    
    for(var i = 0; i < 12; i++) {
      
      $('<div class="col-md-1"></div>').text(decToTime(duration12 * i)).appendTo('#timeline-labels');
      
    }
    
    // Play/Pause
    var videoPlay = function(e) {
      
      e.preventDefault();
      
      var $self = $('#video-play span');
      
      $self.toggleClass('glyphicon-play');
      $self.toggleClass('glyphicon-pause');
      
      if($self.hasClass('glyphicon-play')) _this.$video[0].pause();
      else _this.$video[0].play(); ga('send','event','videos','play','Play video: ' +_this.slug);
      
    }
    
    $('#video-play').on('click', videoPlay);
    $('#video').on('click', videoPlay);

    $('body').on('keydown', function(e) {
      if(e.keyCode == 32 && e.target.localName != 'input') videoPlay(e);
      
    });
    
    // Update current time
    //_this.$video.on('timeupdate', update);
    requestAnimationFrame(update);
    
    // Volume slider
    var $volumeSlider = $('#video-volume'),
        tempVolume = null;
    
    noUiSlider.create($volumeSlider[0], {
      start: 1,
      connect: 'lower',
      range: {
        min: 0,
        max: 1
      }
    });
    
    $volumeSlider[0].noUiSlider.on('update', function(values, handle) {
      
      tempVolume = null;
      
      _this.$video[0].volume = values[handle];
      
      var $volumeIcon = $('#volume-icon');
      
      if(values[handle] == 0) $volumeIcon.attr('class', 'glyphicon glyphicon-volume-off');
      else if(values[handle] > 0.5) $volumeIcon.attr('class', 'glyphicon glyphicon-volume-up');
      else $volumeIcon.attr('class', 'glyphicon glyphicon-volume-down');
      
    });
    
    $('#volume-icon').on('click', function(e) {
      
      e.preventDefault();
      
      if(tempVolume == null) {
        
        var temp = _this.$video[0].volume;
        $volumeSlider[0].noUiSlider.set(0);
        tempVolume = temp;
        
      }
      else {
        
        $volumeSlider[0].noUiSlider.set(tempVolume);
        
      }
      
    });
    
    // Fullscreen support
    
    var launchFullscreen = function(elem) {
      if(elem.requestFullScreen) {
        elem.requestFullScreen();
      }
      else if(elem.mozRequestFullScreen) {
        elem.mozRequestFullScreen();
      }
      else if(elem.webkitRequestFullScreen) {
        elem.webkitRequestFullScreen();
      }
    }
    
    var exitFullscreen = function() {
      
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if(document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      } else if(document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
      } else if(document.msExitFullscreen) {
        document.msExitFullscreen();
      }
      
    };
    
    $('#video-fullscreen').on('click', function(e) {
      
      e.preventDefault();
      
      if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
        
        var videoWrap = $('#video-wrapper'),
            oldH = videoWrap.height(),
            oldW = videoWrap.width(),
            coof = 1;
        
        exitFullscreen();
        
        setTimeout(function() {
          
          if($(window).height() / oldH < $(window).width() / oldW) {
            
            videoWrap.css('height', '100%');
            coof = videoWrap.height() / oldH;
            videoWrap.css('width', coof * oldW);
            
          }
          else {
            
            videoWrap.css('width', '100%');
            coof = videoWrap.width() / oldW;
            videoWrap.css('height', coof * oldH);
            
          }
          
          $('.video-label').each(function(i, elem) {
            
            var left = $(elem).css('left'),
                top = $(elem).css('top');
            
            $(elem).width($(elem).width() * coof).height($(elem).height() * coof).css('left', left.substr(0, left.length - 2) * coof).css('top', top.substr(0, top.length - 2) * coof);
            
          });
          
          dragresize.maxLeft = _this.$video.width();
          dragresize.maxTop = _this.$video.height();
          
        }, 100);
        
        $('#video-wrapper').removeAttr('style');
        
      }
      else {
        
        var videoWrap = $('#video-wrapper'),
            oldH = videoWrap.height(),
            oldW = videoWrap.width(),
            coof = 1;
        
        launchFullscreen(videoWrap[0]);
        
        setTimeout(function() {
          
          if($(window).height() / oldH < $(window).width() / oldW) {
            
            videoWrap.css('height', '100%');
            coof = videoWrap.height() / oldH;
            videoWrap.css('width', coof * oldW);
            
          }
          else {
            
            videoWrap.css('width', '100%');
            coof = videoWrap.width() / oldW;
            videoWrap.css('height', coof * oldH);
            
          }
          
          $('.video-label').each(function(i, elem) {
            
            var left = $(elem).css('left'),
                top = $(elem).css('top');
            
            $(elem).width($(elem).width() * coof).height($(elem).height() * coof).css('left', left.substr(0, left.length - 2) * coof).css('top', top.substr(0, top.length - 2) * coof);
            
          });
          
          dragresize.maxLeft = _this.$video.width();
          dragresize.maxTop = _this.$video.height();
          
        }, 100);
        
      }
      
    });
    
    // End playback
    _this.$video.on('ended', function() {
    
      var $play = $('#video-play span');
      
      $play.removeClass('glyphicon-play');
      $play.removeClass('glyphicon-pause');
      $play.addClass('glyphicon-play');
      
    });
    
    // Slider timeline
    $timelineSlider = $('#timeline-slider');
    
    noUiSlider.create($timelineSlider[0], {
      start: [0],
      connect: 'lower',
      range: {
        'min': 0,
        'max': duration
      }
    });
    
    $timelineSlider[0].noUiSlider.on('slide', function(values, handle) {
      
      _this.$video[0].currentTime = values[handle];
      $currentTime.text(decToTime(values[handle]));
      
    });
    
    //$timelineSlider.find('.noUi-handle').append('<div id="timeline-hr"></div>');
    
    // Adding label
    $('#labelAdd').on('click', function() {
      
      var name = $('#labelName').val(),
          link = $('#labelLink').val(),
          file = $('#labelImg')[0].files[0];
      
     if(file && /^image\//.test(file.type)) {
        
        var reader = new FileReader();

        reader.onload = function(e) {
          
          var id = getId();
          
          labels[id] = new Label(id, name, link, e.target.result);
          
          $('#labelName').val(''),
          $('#labelLink').val(''),
          $('#labelImg').val('');
        
        };
        reader.readAsDataURL(file);

        setTimeout(height_timeline, 1000);
      }
      else {
        
        alert('Выберите файл изображения.');
        
      }
      
    });
    $('#labelAdd2').on('click', function() {
      
      var name = $('#labelName2').val(),
          link = $('#labelLink2').val(),
          file = $('.prepared-variations .variant.active').attr("data-svg");
      
      if(file) {
    
          var id = getId();

          var data;

          $.ajax({
              url: file,
              dataType: "text",
              async: true,
              success: function(msg){
                data = 'data:image/svg+xml;base64,' + btoa(msg);
                labels[id] = new Label(id, name, link, data);
              }
          });
          
          $('#labelName2').val(''),
          $('#labelLink2').val('');
          
        setTimeout(height_timeline, 1000);
      }
      else {
        
        alert('Выберите анимацию.');
        
      }
      
    });

    $('.tags-list .tag .time').on('click', function() {
      _this.$video[0].currentTime = $(this).attr("data-start");
    });
    
    // Settings resize
    dragresize = new DragResize('dragresize', {
      minWidth: 15,
      minHeight: 15,
      maxLeft: _this.$video.width(),
      maxTop: _this.$video.height()
    });
    
    dragresize.isElement = function(elm) {
     if (elm.className && elm.className.indexOf('drsElement') > -1) return true;
    };
    dragresize.isHandle = function(elm) {
     if (elm.className && elm.className.indexOf('drsMoveHandle') > -1) return true;
    };
    
    dragresize.apply(document);
    
  };

  function get_param(name, url) {
      if (!url) url = window.location.href;
      url = url.toLowerCase(); // This is just to avoid case sensitiveness  
      name = name.replace(/[\[\]]/g, "\\$&").toLowerCase();// This is just to avoid case sensitiveness for query parameter name
      var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
          results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

  function height_timeline() {
    var th = $("#timeline").height() + 20;

    $(".timeline-main .noUi-handle").css("height",th + "px");

    if(!$('.timeline-main .noUi-handle').is(':visible') ) {
      $(".timeline-main .noUi-handle").show();
    }
  }
  

  // Загрузка плеера
  _this.init = function() {
    
    _this.$video = $('#video');
    
    _this.$video.on('loadeddata', function() {
      if(_this.type != "create") {
        $.get('/en/video/' + _this.slug + '/json_labels', {}, function(data) {
          
          if(data) {
            data = JSON.parse(data);
            var i = 0;
            
            for(var obj in data) {
              
              var id = getId();

              if(!data[obj].svg)
                data[obj].svg = false;
            
              labels[id] = new Label(id, data[obj].name, data[obj].link, data[obj].data, data[obj].svg, data[obj].width, data[obj].height, data[obj].left, data[obj].top, data[obj].start, data[obj].end);
              
              i++;
              
            }
          }
          
        });
      }
      
      initInterface();
      
      $('#video-controls').show();

      /*
      var hideControls = function(){
        $('#video-controls').hide().slideUp();
      };

      var timeOut = setTimeout(hideControls, 3000); // ожидание показывания дива

      $('#video-wrapper').mousemove(function(){
           clearTimeout(timeOut);
            $('#video-controls').show().slideDown();
           timeOut = setTimeout(hideControls, 3000);
      });*/


      $('#timeline').show("slow", function() {
         height_timeline();
      });

    });
    
    _this.$video.on('error', function() {
      
      alert('Ошибка загрузки видео.');
      
    });
    
    _this.$video.attr('src', _this.$video.attr('data-src'));
    _this.$video.removeAttr('data-src');
    
  };
  
};