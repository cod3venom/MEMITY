$(document).ready(function()
{
        var limit = 6; var start = 0; var action = 'inactive'; var loader_msg= $('#load_data_message'); var type = "latest";
         $("#latest").click(function(){
            type = 'latest';  $(".middle").html('<div id="load_data"></div>');  mm_load(limit, start,type)
         });
         $("#random").click(function(){
            type = 'random'; $(".middle").html('<div id="load_data"></div>');  mm_load(limit, start,type)
         });
         $("#videos").click(function(){
            type = 'mp4'; $(".middle").html('<div id="load_data"></div>');  mm_load(limit, start,type)
         });
         $("#gifs").click(function(){
            type = 'gif'; $(".middle").html('<div id="load_data"></div>');  mm_load(limit, start,type)
         });
        function mm_load(limit, start,type)
        {   
            $.ajax
            ({
                type:'POST',
                 url:"operator.php",
                 data:{'limit':limit, 'start':start, 'type':type},
                 cache:false,
                 async:true,
                 success:function(data)
                 {
                     data = data.trim();
                    $('#load_data').append(data);
                    //$(".post").slideDown("59");
                    //$('body').animate({scrollTop: '+=50px'},10);
                   
                    if(data === '')
                    {
                        console.log("not found");
                        $('#load_data').html('<div id="sad" class="background transp"></div>');
                        $("#load_data").append('<div id="sad_msg">There are no more posts to show right now</div>');
                        ob_clean();
                        action = 'active';
                    }
                    else
                    {
                        loader_msg.html('<div id="loader"></div>'); //please wait
                        action = "inactive";
                    }
                 }
            });
        }
        if(action == 'inactive')
        {
            action = 'active';
            mm_load(limit,start,type);
        }
        $(window).scroll(function(){
          if($(window).scrollTop() + $(window).height() > $("#load_data").height() && action == 'inactive')
          {
           action = 'active';
           start = start + limit;
           setTimeout(function(){
            mm_load(limit, start,type);
           }, 1000);
          }
     });
        function ob_clean()
        {
            //$.ajax({url:"operator.php",})
        }
});