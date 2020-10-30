$(document).ready(function(){
  var operator = "operator.php"; var stamp = (new Date()).getTime();
  $("#notification").click(function(){show_notif();});
   
  $("#addbtn").hover(function(){
      if($("#addbtn>img").attr("src") === "static/img/plus_green.png")
      {
          $("#addbtn>img").attr("src","static/img/plus_white.png")
          return;
      }
      else if($("#addbtn>img").attr("src") === "static/img/plus_white.png")
      {
          $("#addbtn>img").attr("src","static/img/plus_green.png")
          return;
      }
  });
  $("#searchbtn").hover(function(){
      if($("#searchbtn>img").attr("src") === "static/img/search_green.png")
      {
          $("#searchbtn>img").attr("src","static/img/search_white.png")
          return;
      }
      else if($("#searchbtn>img").attr("src") === "static/img/search_white.png")
      {
          $("#searchbtn>img").attr("src","static/img/search_green.png")
          return;
      }
  });
 $('.middle').on('click', function (e) {
    if ($(e.target).closest("#notifications").length === 0) {
        $("#notifications").hide();
    }
  });
 $('.sidebar').on('click', function (e) {
    if ($(e.target).closest("#notifications").length === 0) {
        $("#notifications").hide();
    }
  });
  $('._ad').on('click', function (e) {
    if ($(e.target).closest("#notifications").length === 0) {
        $("#notifications").hide();
    }
  });
function show_notif()
{
    if($("#notifications").css("display") === "none")
    {
        $("#notifications").slideDown("200");
        return;
    }
    if($("#notifications").css("display") === "block")
    {
        $("#notifications").slideUp("200");
        return;
    }
}
$("#emoji_switch").click(function(){
    var emoji = $("#main_emoj");
    if(emoji.css("display") === "none")
    {
        emoji.slideDown("300");
        return;
    }
    else if(emoji.css("display") === "block")
    {
        emoji.slideUp("300");
        return;
    }
});
 $(".Settings_btn").click(function(){
      $("#profile-data").html("");
     _03x1516("settings=","settings");
});
  $(".Relation_btn").click(function(){
   _03x1516("follow="+id,'set'); var btn = $(this);
    if(btn.text() === "Follow")
      {
        btn.text("Unfollow");
        return;  
      }
    else if(btn.text() === "Unfollow")
    {
      btn.text("Follow");
      return;
    }
 });

$(".followers_btn").click(function(){
      $("#profile-data").html("");
     _03x1516("get_relations="+id+"&type=followers","get");
});
$(".followings_btn").click(function(){
  $("#profile-data").html("");
     _03x1516("get_relations="+id+"&type=followings","get");
});
$("#onlinebtn").click(function(){
  _03x1516("online="+stamp,"online");
});
//PROFILE SETTINGS
_03x1516("profile_posts="+id,'post');
_03x1516("top=","top");
_03x1516("notif=","notif");
//setInterval(() => {_03x1516("top=")}, 900000);
function _03x1516(_01x,type)
{
    $.ajax({
        type:"POST",url:operator, data:_01x,cache:false,success:function(_k)
        {
           // console.log(_k);
            _k = _k.trim();
            if(type === 'top')
            {
                top(_k);
            }  
            else if(type==='get')
            {
               get(_k);             
            }
            else if(type === 'post')
            {
              post(_k);
            }
            else if(type === 'settings')
            {
              sett(_k);
            }
            else if(type === 'notif')
            {
              notif(_k);
            }
            else if(type === 'online')
            {
              console.log(_k);
              $("#load_data").remove();
              $(".middle").html('<div class="online_friends flex"></div>');
              online(_k)
            }
        }
    });
}
function top(_k)
{
    $(".all_tops").html(_k);
}
function get(_k)
{
    $("#profile-data").html(""); $("#profile-data").html('<div class="all_followers flex"></div>');$(".all_followers").html(_k);  
    $(".all_followers").slideDown("slow");
    $(".all_followers").css("display","flex");
     if(_k == '')
     {
       $('.all_followers').html('<div id="sad" class="background transp"></div>'); $(".all_followers").append('<div id="sad_msg">There are no users to show right now</div>');
     } 
}
function post(_k)
{
    $("#profile-data").html(_k)
    $(".all_followers").slideDown("slow");
    $(".all_followers").css("display","flex");
    if(_k == '')
    {
       $('.all_followers').html('<div id="sad" class="background transp"></div>');
       $(".all_followers").append('<div id="sad_msg">There are no posts to show right now</div>');
    } 
}
function sett(_k)
{
     $("#profile-data").html(_k);
     $(".settings").slideDown("slow");
}
_03x1516("notif=","notif");
setInterval(function()
{
    $.ajax({type: "POST", url: operator, data: "st=Online&t="+stamp,success: function(_k){
      console.log("PHP "+_k);
      console.log("JS  " + stamp);
      var compar = stamp - _k;
      if(compar <= 50)
      {
        console.log("ONLINE  {DIFFERENCE} -> " + compar);
      }
      else
      {
        console.log("OFFLINE  {DIFFERENCE} -> " + compar);
      }
    }
    });
}, 10000);
setInterval(function()
{
    $.ajax({type: "POST", url: operator, data: "notif=",success: function(_k){
            notif(_k);
        }
    });
}, 10000); // This will "refresh" every 1 second
function notif(_k)
{ 
  $("#notifications").html(_k);
}
function online(_k)
{
  $(".online_friends").html(_k);
}
});
 