$(document).ready(function(){
      $("#"+id).slideDown("50");  
    list();
    document.querySelector('#sendcom').scrollIntoView({ behavior: 'smooth' })
    $("#sendcom").submit(function(e){
        e.preventDefault();
        $.ajax({
            type:"POST",url:"operator.php",data:$(this).serialize(),cache:false,success:function(__k)
            {
                $("#input_newcom").val("");
                list();
            }
        })
    });

    $("#"+id+"_like_emotion_up").click(function(){
        __ks("post="+id+"&type=Like", "Like");
        
        __ks("total="+id+"&type=Angry","Angry");
        __ks("total="+id+"&type=Love","Love");
        __ks("total="+id+"&type=Funny","Funny");  
        __ks("total="+id+"&type=Like","Like");
         
    });
    $("#"+id+"_funny_emotion_up").click(function(){
        __ks("post="+id+"&type=Funny","Funny");

        __ks("total="+id+"&type=Funny","Funny");
        __ks("total="+id+"&type=Like","Like");
        __ks("total="+id+"&type=Angry","Angry");
        __ks("total="+id+"&type=Love","Love"); 
         
         
    });
   $("#"+id+"_love_emotion_up").click(function(){
        __ks("post="+id+"&type=Love","Love");
        
        __ks("total="+id+"&type=Love","Love");
        __ks("total="+id+"&type=Funny","Funny");
        __ks("total="+id+"&type=Like","Like"); 
        __ks("total="+id+"&type=Angry","Angry");
         
    });
    $("#"+id+"_angry_emotion_up").click(function(){
        __ks("post="+id+"&type=Angry","Angry");

        __ks("total="+id+"&type=Funny","Funny");  
        __ks("total="+id+"&type=Angry","Angry");
        __ks("total="+id+"&type=Like","Like");
        __ks("total="+id+"&type=Love","Love");
         
    });
    function __ks(__Sx,__0x1)
    {
        $.ajax({type:"POST",url:"operator.php",data:__Sx,cache:false,async:true,success:function(_z3r0p)
        {
            if(__0x1 === "Like")
            {   
                if(_z3r0p ==="")
                    $("#liketotal_"+id+"").html("0");
                else
                    $("#liketotal_"+id+"").html(_z3r0p);
            }
            if(__0x1 === "Funny")
            {
                if(_z3r0p =="")
                    $("#funnytotal_"+id+"").html("0");
                else
                    $("#funnytotal_"+id+"").html(_z3r0p);
            }
            if(__0x1 === "Love")
            {
                if(_z3r0p ==="")
                     $("#lovetotal_"+id+"").html("0");
                else
                    $("#lovetotal_"+id+"").html(_z3r0p);
            }
            if(__0x1 === "Angry")
            {
                if(_z3r0p ==="")
                    $("#angrytotal_"+id+"").html("0");
                else
                    $("#angrytotal_"+id+"").html(_z3r0p);
            }
            console.log(_z3r0p);

        }
        });
    }
    function list()
    {
        $.ajax({type:"POST",url:"operator.php",data:"load="+id,cache:false,success:function(_KK)
        {
            $(".all_comments").html("");
            $(".all_comments").html(_KK);}
        });
    }  
});
 
window.onload = function(){
    var new_inp= document.getElementById("input_newcom");
    var submit_nwcomm = document.getElementById("input_switch_detail");
    
    new_inp.oninput = function(){
    new_inp.style.height = "";
    new_inp.style.height = Math.min(new_inp.scrollHeight) + 30 + "px";
    submit_nwcomm.style.marginTop = Math.min(new_inp.scrollHeight) -29 +"px";
    };
 };