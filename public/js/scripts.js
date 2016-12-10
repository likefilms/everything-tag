$(document).ready(function() {

	var main_height = $("body").height();
	var main_width = $("body").width();
	$(".right-line").css('height',main_height + "px");

	$(".pluso .pluso-more").hide();

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

	var editTitle = function(title,id) {
		
		$.ajax({
          type: 'post',
          url: './index.php',
          data: { type: 'editTitle', title: title, id: id },
          success: function(data) {
            //alert(data);
          }
        });
	}

	$("#TitleVideo").focusout(function(e) {
		var title = $(this).val();

		if(title!="") {
			$(".edit-top a.title").text(title).show();
			$(".edit-top .edit-title").hide();

			var id = getVars.id;
	        if(id)
	        	editTitle(title, id);
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

	$(".prepared-variations .variant").click(function() {
		$(".prepared-variations .variant").removeClass("active");
		$(this).addClass("active");
	});

	$(".prepared-variations .variant").hover(function(){
			src = $(this).find("img").attr("src").split("/")[1].split(".")[0];

			if(src=="itag_01" || src=="itag_02") {
				src = "svg/" + src + "_w.svg";
			} else {
				src = "svg/" + src + ".svg";
			}

		    $(this).find("img").attr("src",src);
	    }, function(){
		    src = $(this).find("img").attr("src").split("/")[1].split(".")[0];

		    if(src=="itag_01_w" || src=="itag_02_w") {
				src = "img/" + src.split("_w")[0] + ".png";
			} else {
				src = "img/" + src.split("_w")[0] + ".png";
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

	    var files = evt.dataTransfer.files; // FileList object
	    //$("#uploadFile").val(files[0])
	}

	function handleDragOver(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
	}


  /*
  var dropZone = document.getElementById('drop_zone');
  dropZone.addEventListener('dragover', handleDragOver, false);
  dropZone.addEventListener('drop', handleFileSelect, false); */

  $(".container.list table.table").tablesorter({
  	headers: { '0': {sorter: false}, '2': {sorter: false}, '3': {sorter: false}, '4': {sorter: false}, '5': {sorter: false} }
  });

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