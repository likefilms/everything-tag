var Editor = new function() {
  
  (function() {
    var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
                                window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
    window.requestAnimationFrame = requestAnimationFrame;
  })();

  $.getScript("/js/jquery.spritely.js", function(){

    console.log("Script loaded but not necessarily executed.");

  });
  
  var _this = this,
      type,
      duration,
      $timelineSlider,
      currentTime = 0,
      $currentTime = $('#currentTime'),
      labels = {},
      saved_labels = {},
      added_labels = {},
      slug,
      id,
      oldW,
      oldH,
      dragresize;
  
  _this.labels = labels;
  
  var Label = function(id, type, name, link, data, svg = 0, width, height, left, top, startI, endI) {
    
    var self = this;
    
    this.id = id;
    this.name = name;
    this.link = link;
    this.is_svg = '';
    this.type = type;
    this.svg = svg;

    this.data = data;

    if(svg == 1)  {
      this.is_svg = ' is_svg';
      this.data = "/img/etag_badge_test_65-108mm.svg";
    }

    this.start = 0;
    this.end = duration;
    
    if(width) {
      
      this.start = startI;
      this.end = endI;
      
    }

    if(_this.type=='editor' || _this.type=='create') {
      var class_label = "drsElement drsMoveHandle";
      if(svg == 1) {
        class_label += " is_svg";
      }
    } else {
      var class_label = "";
    }

    var path_img = "";
    if(width) path_img = "/uploads/tags/";

    if(type != "user") {
      if(_this.type=='editor' || _this.type=='create') {
        if(svg == 1) {
          var labelVideo = $('<div class="video-label {class}" id="{id}"><img class="animate-tag tag1" src="{data}"></div>'.replace(/{data}/g, this.data).replace(/{class}/g, class_label).replace(/{id}/g, "video-label" + this.id));
        } else {
          var labelVideo = $('<div class="video-label {class}" id="{id}"><img src="{data}"></div>'.replace(/{data}/g, path_img + this.data).replace(/{class}/g, class_label).replace(/{id}/g, "video-label" + this.id));
        }
      } else {
        if(svg == 1) {
          var labelVideo = $('<div data-plyr="publish_label" class="video-label publish_link {class}"><a onclick="{ga}" data-save="false" href="{link}"><img class="animate-tag tag1" src="{data}"></a></div>'.replace(/{data}/g, this.data).replace(/{class}/g, class_label).replace(/{id}/g, "video-label" + this.id));
        } else {
          var labelVideo = $('<div data-plyr="publish_label" class="video-label publish_link {class}"><a onclick="{ga}" data-save="false" href="{link}"><img src="/uploads/tags/{data}"></a></div>'.replace(/{data}/g, this.data).replace(/{class}/g, class_label).replace(/{link}/g, this.link).replace(/{ga}/g,"ga('send','event','tags','click','Name tag: " + this.name + "');"));
        }

        var labelSaved = $('<div class="tag"><span data-start="{start}" class="time">{time}</span><a target="_blank" href="{link}">{name}</a><span class="share"></span></div>'.replace(/{name}/g, this.name).replace(/{link}/g, this.link).replace(/{time}/g, decToTime(this.start)).replace(/{start}/g, this.start));

        var labelRemained = $('<div class="tag"><span data-start="{start}" class="time">{time}</span><a target="_blank" href="{link}">{name}</a><span class="share"></span></div>'.replace(/{name}/g, this.name).replace(/{link}/g, this.link).replace(/{time}/g, decToTime(this.start)).replace(/{start}/g, this.start));
      }

      var labelImg = labelVideo.find('img');
      
      if(width) {

        var coofW = _this.$video.width() / instances[0].getMedia().videoWidth,
            coofH = _this.$video.height() / instances[0].getMedia().videoHeight;
        
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

      labelVideo.appendTo('.plyr__video-wrapper');

      if(_this.type=='show') {
        labelRemained.appendTo('.remained-tags-list');
      }
      
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

            if(!labelBlock.find('.label-name').hasClass("new")) {
              $.ajax({
                type: 'post',
                url: '/api/tags/delete/' + self.id,
                data: data,
                success: function(data) {

                }
                
              });  
            }
            
            labelBlock.remove();
            labelVideo.remove();
            
            delete(labels[self.id]);

            $("#edit-tags").hide();

            // Обновляем высоту ползунка
            var th = $(".timeline-main .noUi-handle").height() - 22;
            $(".timeline-main .noUi-handle").css("height",th + "px");
            
          }
          
        });

        // Обновляем высоту ползунка
        var th = 22 + $(".timeline-main .noUi-handle").height();
        $(".timeline-main .noUi-handle").css("height",th + "px");

        labelVideo.find('a').on('click', function(e) {
          e.preventDefault();

          //window.open($(this).attr("href"),'_blank');window.open('#','_self');

          if($(this).attr("data-save") == "false") {
            labelRemained.remove();

            $('#saved-tag > span').remove();
            labelSaved.appendTo('#saved-tag');

            $(".bookmark_count").text(parseInt($(".bookmark_count").text()) + 1);
            $(this).attr("data-save", "true");

            if($(".remained-tags-list > .tag").length == 0) $(".remained-tags-list, h4.remained").hide();
          }
        });

        if(_this.type=='show') {
          labelSaved.find('.time').on('click', function() {
            instances[0].getMedia().currentTime = $(this).attr("data-start");
            $("button[data-plyr=bookmark]").click();
          });    

          labelRemained.find('.time').on('click', function() {
            instances[0].getMedia().currentTime = $(this).attr("data-start");
            $("button[data-plyr=bookmark]").click();
          });

          labelSaved.find('.share').on('click', function() {
            $(".plyr__controls_bookmark .share_tag").show();
          });    

          labelRemained.find('.share').on('click', function() {
            $(".plyr__controls_bookmark .share_tag").show();
          });
        }


        // Дабавляем теги в таймлайн редактора
        if(_this.type=="editor" || _this.type=='create') {
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
          
          labelBlock.appendTo('#timeline');
        }


    } else {
      var labelAdded = $('<div class="tag"><span class="time">{start}</span><a href="{link}">{name}</a><span class="share"></span></div>'.replace(/{name}/g, this.name).replace(/{link}/g, this.link).replace(/{start}/g, decToTime(this.start)));
      
      $('#added-tag > span').remove();
      labelAdded.appendTo('#added-tag');

      $(".bookmark_count").text(parseInt($(".bookmark_count").text()) + 1);
    }
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
    
    currentTime = instances[0].getCurrentTime();
      
    //$timelineSlider[0].noUiSlider.set(_this.$video[0].currentTime);
    
    for(var l in labels) {

      l = labels[l];

      if(l.type == "user") continue;
      
      if(currentTime >= l.start && currentTime <= l.end) {
        l.elem.show();

        // Отправляем аналитику
        //if(_this.type!="editor" || _this.type!="create") ga('send','event','tags','show','Name tag: ' + l.name);

        if(l.elem.find("img").attr("src")=="") {
          var path_img = "/uploads/tags/";
          
          if(l.data.indexOf("data:image/") != -1)
            path_img = "";

        //l.elem.find("img").attr("src", path_img + l.data);
        }
      } else {
        l.elem.hide();
        //l.elem.find("img").attr("src","");
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
            coofW = instances[0].getMedia().videoWidth / _this.$video.width(),
            coofH = instances[0].getMedia().videoHeight / _this.$video.height();   
        
        for(var label in labels) {

          if(labels[label].type == "user")
            continue;
          
          var left = labels[label].elem.css('left'),
              top = labels[label].elem.css('top');
          
          data_labels[i] = {
            token: $("input[name=_token]").val(),
            method: "PUT",
            id: labels[label].id,
            type: labels[label].type,
            video_id: _this.id,
            user_id: _this.user_id,
            name: labels[label].name,
            link: labels[label].link,
            svg: labels[label].svg,
            start: labels[label].start,
            end: labels[label].end,
            image: labels[label].data,
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
          description: "",
          keywords: ""
        }

        data.labels = JSON.stringify(data_labels);
        
        var self = $(this);
        self.addClass('disabled').text('Видео сохраняется...');
        $('.progress').show().find('div').css('width', '0%').find('span').text('0%');
        
        $.ajax({
          type: 'post',
          url: '/api/videos/' + method + '/' + _this.slug,
          data: data,
          success: function(data) {

            setTimeout(function() {
              window.location.href = "/en/video/" + _this.slug + "/edit";
            }, 1000);

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
    duration = instances[0].getDuration();

    $('#fullTime').text(decToTime(duration));
    
    var duration12 = duration / 11;
    
    for(var i = 0; i < 12; i++) {
      
      $('<div class="col-md-1"></div>').text(decToTime(duration12 * i)).appendTo('#timeline-labels');
      
    }
    
    // Update current time
    //_this.$video.on('timeupdate', update);
    requestAnimationFrame(update);

    // Fullscreen
    $("button[data-plyr=fullscreen]").click(function() {
      var videoWrap = $('.plyr__video-wrapper');
        _this.oldH = videoWrap.height();
        _this.oldW = videoWrap.width();
    });   

    instances[0].on('enterfullscreen', function(event) { 
      var videoWrap = $('.plyr__video-wrapper');
      coof_basic = 1;

      console.log($(".plyr__video-wrapper").width());
      console.log($(".plyr__video-wrapper").height());

      setTimeout(function() {
        
        if($(window).height() / _this.oldH < $(window).width() / _this.oldW) {
          coof_basic = videoWrap.height() / _this.oldH;
        }
        else {
          coof_basic = videoWrap.width() / _this.oldW;
        }

        console.log("coof_basic: " + coof_basic);

        $('.video-label').each(function(i, elem) {
          
          var left = $(elem).css('left'),
              top = $(elem).css('top');

          if(i == 0) {
            console.log("Tag width: " + $(elem).width() * coof_basic);
            console.log("Tag height: " + $(elem).height() * coof_basic);
            console.log("Tag left: " + left.substr(0, left.length - 2) * coof_basic);
            console.log("Tag top: " + top.substr(0, top.length - 2) * coof_basic);
          }    
          
          $(elem).width($(elem).width() * coof_basic).height($(elem).height() * coof_basic).css('left', left.substr(0, left.length - 2) * coof_basic).css('top', top.substr(0, top.length - 2) * coof_basic);
          
        });
        
        dragresize.maxLeft = _this.$video.width();
        dragresize.maxTop = _this.$video.height();
        
      }, 100);
    });

    instances[0].on('exitfullscreen', function(event) {
      var videoWrap = $('.plyr__video-wrapper');
      coof_full = 1;

      console.log($(".plyr__video-wrapper").width());
      console.log($(".plyr__video-wrapper").height());
      
      setTimeout(function() {
        
        if($(window).height() / _this.oldH < $(window).width() / _this.oldW) {
          coof_full = videoWrap.height() / _this.oldH;
        }
        else {
          coof_full = videoWrap.width() / _this.oldW;
        }

        console.log("coof_full: " + coof_full);
        
        $('.video-label').each(function(i, elem) {
          
          var left = $(elem).css('left'),
              top = $(elem).css('top');

          if(i == 0) {
            console.log("Tag width: " + $(elem).width() * coof_full);
            console.log("Tag height: " + $(elem).height() * coof_full);
            console.log("Tag left: " + left.substr(0, left.length - 2) * coof_full);
            console.log("Tag top: " + top.substr(0, top.length - 2) * coof_full);
          }    

          $(elem).width($(elem).width() * coof_full).height($(elem).height() * coof_full).css('left', left.substr(0, left.length - 2) * coof_full).css('top', top.substr(0, top.length - 2) * coof_full);
          
        });
        
        dragresize.maxLeft = _this.$video.width();
        dragresize.maxTop = _this.$video.height();
        
      }, 100);
    });

    if(_this.type=="editor" || _this.type=='create') {
        $timelineSlider = $('#timeline-slider');
          
        noUiSlider.create($timelineSlider[0], {
          start: [0],
          connect: 'lower',
          range: {
            'min': 0,
            'max': instances[0].getDuration()
          }
        });
        
        $timelineSlider[0].noUiSlider.on('slide', function(values, handle) {
          instances[0].getMedia().currentTime = values[handle];
        });

        $timelineSlider.find('.noUi-handle').append('<div id="timeline-hr"></div>');
    }
    
    // Adding label
    $('#labelAdd').on('click', function() {
      
      var name = $('#labelName').val(),
          link = $('#labelLink').val(),
          file = $('#labelImg')[0].files[0];
      
     if(file && /^image\//.test(file.type)) {
        var reader = new FileReader();

        reader.onload = function(e) {
          
          var id = getId();
          
          labels[id] = new Label(id, "publisher", name, link, e.target.result);

          $(".row.lbl .label-name[data-id=" + id + "]").addClass("new");
          
          $('#labelName').val(''),
          $('#labelLink').val(''),
          $('#labelImg').val('');
          $("#add-tag").click();
        };

        reader.readAsDataURL(file);
      }
      else {
        
        alert('Выберите файл изображения.');
        
      }
    });

    $('#labelAdd2').on('click', function() {
      
      var name = $('#labelName2').val(),
          link = $('#labelLink2').val();
      
      if($('.prepared-variations .variant').hasClass("active")) {
    
          var id = getId();

          labels[id] = new Label(id, "publisher", name, link, "", 1);

          $(".row.lbl .label-name[data-id=" + id + "]").addClass("new");
          
          $('#labelName2').val(''),
          $('#labelLink2').val('');
          $("#add-tag").click();
      }
      else {
        
        alert('Выберите анимацию.');
        
      }
      
    });

    $("button[data-plyr=bookmark]").on('click', function() {
      $(this).toggleClass("active");

      if($(this).hasClass("bookmark-pause")) {
        instances[0].togglePlay();
        $(this).removeClass("bookmark-pause");
      } else {
        if(!instances[0].isPaused()) {
          instances[0].togglePlay();
          $(this).addClass("bookmark-pause");
        } 
      }

      $("video.plyr--setup, div.video-label").toggleClass("vi_blur");

      if($(this).hasClass("active")) {
        $(".plyr__controls_bookmark").show();
        $(".plyr__controls").hide();
      } else {
        $(".plyr__controls_bookmark").hide();
        $(".plyr__controls").show();
      }
    });    

    $("button[data-plyr=back]").on('click', function() {
      $("button[data-plyr=bookmark]").click();
    });

    $("button[data-plyr=addtag]").on('click', function() {
        $(this).toggleClass("active");

        $.ajax({
          type: 'post',
          url: '/api/tags/spfng_uri_encode',
          success: function(slug) {
            var link = "https://everythingtag.com/en/tags/" + slug;
            $('input#copyTagLink').attr("value", link);
            var time = decToTime(instances[0].getCurrentTime());

            ///
            var data = {
              token: $("input[name=_token]").val(),
              method: "PUT",
              user_id: _this.user_id,
            };

            data.type = "user";
            data.video_id = _this.id;
            data.user_id = _this.user_id;
            data.start = instances[0].getCurrentTime();
            data.end = instances[0].getDuration();

            data.en = {
              title: "My tag " + time,
              slug: slug,
              status: 1,
            }

            $.ajax({
                type: 'post',
                url: '/api/tags/create/' + slug,
                data: data,
                success: function(data) {
                  addAndCopyLink();
                }
            });
          }
        });
    });

    function addAndCopyLink() {
      var time = decToTime(instances[0].getCurrentTime());
      var link = $('input#copyTagLink').attr("value");

      /*
      // Копируем ссылку в буффер
      document.getElementById('copyTagLink').select();
      var succeeded;
      try {
        // Copy the selected text to clipboard
        succeeded = document.execCommand("copy");
      } catch (e) {
        succeeded = false;
      }
      if (succeeded) {
        
      }*/

      $(".bookmark_count").text(parseInt($(".bookmark_count").text()) + 1);

      $(".plyr__tag_save").fadeIn( 1000, function() {
        setTimeout(function() {$(".plyr__tag_save").fadeOut("fast");}, 1000);
      });

      $("#added-tag > span").remove();

      var tag = $('<div class="tag"><span data-start="{start}" class="time">{time}</span><a target="_blank" href="{link}">{name}</a><span class="share"></span></div>'.replace(/{name}/g, "My tag " + time).replace(/{link}/g, link).replace(/{time}/g, time).replace(/{start}/g, instances[0].getCurrentTime()));
      tag.appendTo("#added-tag");
    }

    $('.plyr--setup').on('click', function(e) {
      instances[0].togglePlay();
    });

    $('.tags-list .tag .time, #added-tag .tag .time').on('click', function() {
      instances[0].getMedia().currentTime = $(this).attr("data-start");
    });
    
    // Settings resize
    dragresize = new DragResize('dragresize', {
      minWidth: 15,
      minHeight: 15,
      maxLeft: _this.$video.width(),
      maxTop: _this.$video.height()
    });
    
    dragresize.isElement = function(elm) {
      if ($(elm).hasClass('drsElement')) return true;
    };
    dragresize.isHandle = function(elm) {
      if ($(elm).hasClass('drsMoveHandle')) return true;
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

  // Загрузка плеера
  _this.init = function() {
    
    _this.$video = $(instances[0].getContainer());

    _this.jsonLoad = false;
    _this.playLoad = false;

    instances[0].on('canplaythrough', function(event) {
             
      if(_this.type != "create" && _this.jsonLoad == false) {

        $.get('/en/video/' + _this.slug + '/json_labels', {}, function(data) {
          
          if(data) {
            data = JSON.parse(data);
            var i = 0;
            
            for(var obj in data) {
              
              var id = data[obj].id;

              if(!data[obj].svg)
                data[obj].svg = false;
            
              labels[id] = new Label(id, data[obj].type, data[obj].name, data[obj].link, data[obj].data, data[obj].svg, data[obj].width, data[obj].height, data[obj].left, data[obj].top, data[obj].start, data[obj].end);
              
              i++;
              
            }
          }

          
          
        });

        _this.jsonLoad = true;
      }

      if(_this.playLoad == false) {
        initInterface();

        $('#timeline').show("slow", function() {});

        _this.playLoad = true;
      }

      $(".pluso .pluso-more").hide();

    });
    
    instances[0].on('error', function() {
      
      alert('Ошибка загрузки видео.');
      
    });
    
  };
  
};