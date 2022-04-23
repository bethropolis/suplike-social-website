const _set_user = sessionStorage.getItem('user')||null; 
if (_set_user != null) { 
   $.post('./inc/online.inc.php',{
       user: _set_user 
   });  
}  
let audio = new Audio('./img/notify.mp3');
// Language: javascript
// this function will repeat every 10 seconds
function checkNew(){
    $.get("./inc/notification.inc.php?new",function(data){
        if(data.new){
            audio.play(); 
            setTimeout(audio.pause,1000);
        }
    }
    );
    setTimeout(checkNew, 10000);
}
checkNew();
