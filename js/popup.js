var popupStatus = 0;
var editor_id;
function loadPopup(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#UploadCenter").fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#UploadCenter").fadeOut("slow");
		popupStatus = 0;
	}
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = $(window).width();
	var windowHeight = $(window).height();
	var popupHeight = $("#UploadCenter").height();
	var popupWidth = $("#UploadCenter").width();
	$top=windowHeight/2-popupHeight/2;
	if($top<0)
		$top=0;
	$left=windowWidth/2-popupWidth/2
	if($left<0)
		$left=0;
	//centering
	$("#UploadCenter").css({
		"position": "fixed",
		"top": $top,
		"left": $left 
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}


//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	$(".UploadCenterButton").click(function(e){
		e.preventDefault();
		centerPopup();
		loadPopup();
		editor_id=$(this).attr("rev");
	});
					
	$("#UploadCenterClose").click(function(){
		disablePopup();
	});
	$("#backgroundPopup").click(function(){
		disablePopup();
	});
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup();
		}
	});
});


function p_editor_insertHTML(html) {
	var ed = tinyMCE.get(editor_id);
	var bm = ed.selection.getBookmark();
	ed.execCommand("mceInsertContent", false, html);
	ed.selection.moveToBookmark(bm);
}