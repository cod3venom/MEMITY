$(document).ready(function(){

	$("#addbtn").click(function(){
		show_modal("#add_post_modal");
 	});
	$(".close").click(function(){
      $(".modal").slideUp("slow");
  });
	function show_modal(id)
	{
		var modal = $(id);
		if(modal.css("display")==="block")
		{
			modal.slideLeft("slow");
			modal.slideUp("slow");
		}
		else
		{
			modal.slideDown("slow");
		}
	}
	var desc_ptr = document.getElementById("desc_inptr");
	desc_inptr.oninput = function(){
		desc_ptr.style.height = '';
		desc_ptr.style.height = Math.min(desc_ptr.scrollHeight) + 30 + "px";
 	};
 	$("#selected").click(function(){
 		$("#upf>input[type='file']").click();
 	});
	 $("#removeimg").click(function(){
	 	$("#selected").attr("src","static/img/upload.png");
	 	$("#selected").css("width","100px");
	 	$("#selected").css("height","100px");
	 });
});
 function selectPostImage(event)
{ 
	var selectedfile = event.target.files[0];
 	var reader = new FileReader();
 	var img_tag = document.getElementById("selected");
 	img_tag.title = selectedfile.name;
 	var c = selectedfile.name.match(/\./g).length;
 	var e = selectedfile.name.split(".")[c];
     reader.onload = function(event)
    {
    	if(e === 'mp4' || e === 'webp')
	 	{
	 		$("#selected").css("display","none"); 
	 		$(".up_cover").html('<video controls><source src="'+event.target.result+'"</source></video>');
	 		$(".up_cover>video").css("width","100%"); $(".up_cover>video").css("height","140px"); 
	 		$("#removeimg").css("display","block");
 	 		//console.log("FILETYPE IS ACCEPTED {VIDEO} " + e);
	 	}
	 	else if(e === 'png' || e === 'jpg' || e === 'jpeg' || e === 'bmp' || e === 'gif')
	 	{
	 		//console.log("FILETYPE IS ACCEPTED {IMAGE} " + e);
	 		img_tag.src = event.target.result;
	     	img_tag.style.display = "block";
	     	img_tag.style.width = "100%";
	     	img_tag.style.height = "140px";
	     	$("#removeimg").css("display","block");
	 	}
	 	else
	 	{
	 		console.log("WRONG FILE TYPE");
	 	}
    };
    reader.readAsDataURL(selectedfile);
}