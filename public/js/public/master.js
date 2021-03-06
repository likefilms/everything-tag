/**
 * Fancyboxes
 */
$(".fancybox").fancybox({
    prevEffect: 'fade',
    nextEffect: 'fade',
    openEffect: 'elastic',
    closeEffect: 'elastic'
});

/**
 * Sliders
 */
var mySwiper = new Swiper('.swiper-container', {
    loop: true,
    grabCursor: true,
    autoplay: 6000,
    setWrapperSize: true,
    // slidesPerView: 'auto',
    // spaceBetween: 30,
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev'
});

function getVideo(videoID) {
    document.getElementById("video_info").innerHTML="Getting information... please wait.";
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          document.getElementById("video_info").innerHTML=xmlhttp.responseText;
        }
      }
  xmlhttp.open("GET","youtube/download.php?videoid="+videoID,true);
  xmlhttp.send();
}

$(document).ready(function() {

    var main_height = $("body").height();
    var main_width = $("body").width();
    $(".right-line").css('height',main_height + "px");

    $(".video-social .share .pluso-more").hide();

    $("#add-tag").click(function() {
        $(this).toggleClass("show");
        
        var status = $(this).attr("class");

        if(status == "show") {
            $(".control-tags").show();
            $("#static .control-scroll").tinyscrollbar();
        } else {
            $(".control-tags").hide();

        }
    });

    $(".edit-top a.title").click(function(e) {
        e.preventDefault();
        $(this).hide();
        $(".edit-top .edit-title").show();

        $("#TitleVideo").focus();
        var tmpStr = $("#TitleVideo").val();
        $("#TitleVideo").val('');
        $("#TitleVideo").val(tmpStr);
    });

    $("#TitleVideo").focusout(function(e) {
        var title = $(this).val();

        if(title!="") {
            $(".edit-top a.title").text(title).show();
            $(".edit-top .edit-title").hide();
        }
    })

    $("a.delete-video").click(function(e) {
        if (confirm("Are you sure?")) {
          return true;
        } else {
          return false;
        }
    }); 

    $(".type-tag li a").click(function(e) {
        e.preventDefault();
        var status = $(this).attr("class");

        if(status == "static") {
            $(".type-tag li:nth-child(2)").removeClass("active");
            $(".type-tag li:first-child").addClass("active");
            $("form#static").show();
            $("form#animated").hide();
            $("#static .control-scroll").tinyscrollbar();
        } else {
            $(".type-tag li:first-child").removeClass("active");
            $(".type-tag li:nth-child(2)").addClass("active");
            $("form#static").hide();
            $("form#animated").show();
            $("#animated .control-scroll").tinyscrollbar();
        }
    });

    $(".tags-tab li").click(function(e) {
        var status = $(this).attr("data-id");

        if(status == "saved") {
            $(".tags-tab li:nth-child(2)").removeClass("active");
            $(".tags-tab li:first-child").addClass("active");
            $("#saved-tag").show();
            $("#added-tag").hide();
        } else {
            $(".tags-tab li:first-child").removeClass("active");
            $(".tags-tab li:nth-child(2)").addClass("active");
            $("#saved-tag").hide();
            $("#added-tag").show();
        }
    });

    $(".prepared-variations .variant").click(function() {
        $(".prepared-variations .variant").removeClass("active");
        $(this).addClass("active");
    });

    $(".prepared-variations .variant").hover(function(){
            src = $(this).find("img").attr("src").split("/")[2].split(".")[0];

            if(src=="itag_01" || src=="itag_02") {
                src = "/svg/" + src + "_w.svg";
            } else {
                src = "/svg/" + src + ".svg";
            }

            $(this).find("img").attr("src",src);
        }, function(){
            src = $(this).find("img").attr("src").split("/")[2].split(".")[0];

            if(src=="itag_01_w" || src=="itag_02_w") {
                src = "/img/" + src.split("_w")[0] + ".png";
            } else {
                src = "/img/" + src.split("_w")[0] + ".png";
            }

            $(this).find("img").attr("src", src);
    });

    $(".volume-block").hover(function(){
        $("#video-volume").show();
        }, function(){
        $("#video-volume").hide();
    });

    $(".uploadFile #bg-border,.uploadFile .upload-text").click(function() {
        $(this).parent().find("input[type=file]").click();
    });

    $("input[type=file]").change(function(e) {
        if($(this).val() != "") {
            var filename = $(this).val().replace(/\\/g,'/').replace( /.*\//, '' ).slice(0, 23);
            html = "<span>Your file</span> <small>" + filename + "</small>";
            $(this).parent().find(".upload-text").html(html);
        }
    });


    // Загрузка файла с главной страницы
    $("#upload_form").submit(function(e) {
      e.preventDefault();

        if($("input[type=file]")[0].files.length > 0 || $("input[name=uploadYoutube]").val()) {
          $("img.loading-video-file").show();

          var data = new FormData();

          if($("input[name=uploadYoutube]").val()) {
            var method = "uploadYoutube";
            data.append("link", $("input[name=uploadYoutube]").val());
            data.append("type", $("input[name=typeYoutube]").val());
          } else {
            var method = "upload_video";
            data.append("name", $("input[type=file]")[0].files[0]);
          }

          $.ajax({
            type: 'post',
            url: "api/videos/" + method,
            data: data,
            cache : false,
            processData: false,
            contentType: false,
            success: function(filename) {
              window.location.href = "/en/video/create?file=" + filename;
            }
          });
        }
    });

    var getVars = new function() { 
       var $_GET = {}; 
       var __GET = window.location.search.substring(1).split("&"); 
       for(var i=0; i<__GET.length; i++) { 
          var getVar = __GET[i].split("="); 
          $_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1]; 
       } 
       return $_GET; 
    }


    function handleFileSelect(evt) {
        evt.stopPropagation();
        evt.preventDefault();

        drop = $("#" + evt.target.id);
        var files = evt.dataTransfer.files; // FileList object
        drop.siblings("input[type=file]").prop('files', files);

        drop.removeClass("active");
    }

    function handleDragOver(evt) {
        evt.stopPropagation();
        evt.preventDefault();

        drop = $("#" + evt.target.id);
        drop.addClass("active");
        evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
    }

    if($("#drop_zone").length > 0 || $("#drop_zone_label").length > 0) {
      var dropZone = document.getElementById('drop_zone');
      dropZone.addEventListener('dragover', handleDragOver, false);
      dropZone.addEventListener('drop', handleFileSelect, false);     

      var dropZoneLabel = document.getElementById('drop_zone_label');
      dropZoneLabel.addEventListener('dragover', handleDragOver, false);
      dropZoneLabel.addEventListener('drop', handleFileSelect, false); 
    }

  if($("div").is(".container.list table.table")) {
     $(".container.list table.table").tablesorter({
      headers: { '0': {sorter: false}, '2': {sorter: false}, '3': {sorter: false}, '4': {sorter: false}, '5': {sorter: false} }
    }); 
  }


    var r = 0;
    setSize();

    function setSize() {
      width = $(window).width();
        height = $(window).height();
      r = Math.sqrt(width * width + height * height);
    }
    $(window).resize(setSize);

  $('a#share').click(function(e) {
      e.preventDefault();

      $(this).toggleClass("current");

      if($(this).hasClass("current")) {
        $('.video-social .share').show();
      } else {
        $('.video-social .share').hide();
      }
     
    });

});
