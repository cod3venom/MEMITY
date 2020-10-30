$("#newawselect").click(function(){
    $("#selectorig").click();
});
$("#changeuser").submit(function(e){
	 e.preventDefault();var data = $(this);
	_alt34x(data.serialize());
});
$("#changemail").submit(function(e){
	 e.preventDefault();var data = $(this);
	_alt34x(data.serialize());
});
$("#changepwd").submit(function(e){
	 e.preventDefault();var data = $(this);
	_alt34x(data.serialize());
});
function _alt34x(_x01ex)
{
	$.ajax({
		type:"POST",url:"operator.php",data:_x01ex,cache:false,success:function(_f03)
		{
 			_f03 = _f03.trim();
			if(_f03==='1'){
				_f105();
			}
			if(_f03==='0'){
				 _501f();
			}	 
		}
	});
}
function _f105(){
	$(".settings_security>span").css("color","red");
	$(".settings_security>span").text("Password doesn't match");
}
function _501f(){
	$(".settings_security>span").css("color","#4AB480");
	$(".settings_security>span").text('Password has been updated');
}
function selectAv(event)
{ var selectedfile = event.target.files[0]; var reader = new FileReader();var img_tag = document.getElementById("selected_img");var upload_title = document.getElementById("newawselect");img_tag.title = selectedfile.name;
    reader.onload = function(event)
    {
        img_tag.src = event.target.result; img_tag.style.display = "block";upload_title.style.display = "none";
    };
    reader.readAsDataURL(selectedfile);
}