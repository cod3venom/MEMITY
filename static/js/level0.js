$(document).ready(function(){

$("#regist_switch").click(function(){auth_switch()});
$("#login_switch").click(function(){auth_switch()});
function auth_switch()
{
    if($(".auth_page").css("display") === 'block')
    {
        $(".auth_page").slideUp("300");
        $(".registpage").slideDown("300");
        return;
    }
    if($(".registpage").css("display") === 'block')
    {
        $(".registpage").slideUp("300");
        $(".auth_page").slideDown("300");
        return;
    }
    
}
$("#loginform").submit(function(e){
    e.preventDefault();
    _0x_15($(this).serialize());
});
$("#registform").submit(function(e){
    e.preventDefault();
    _0x_15($(this).serialize());
});
function _0x_15(_0xlq)
{
    $.ajax({
        type:"POST",url:"operator.php",data:_0xlq,cache:false,success:function(_0x3)
        {
            console.log(_0x3);
            _0x3 = _0x3.trim();
            if(_0x3 === "1")
            window.location.href = "http://localhost/meme/";
            else if(_0x3 === "2")
                $("#page_title").text("User with given email already exists");
            else if(_0x3 === "3")
                $("#page_title").text("Please enter correct email and username");
            else if(_0x3 === "4")
                $("#page_title").text("Please fill all values");
            else if(_0x3 === "5")
                $("#page_title").text("Please enter correct credentials");
        }
    });
}
});