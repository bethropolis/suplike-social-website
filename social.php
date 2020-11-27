<?php
require "header.php";
?>

 <main>

 <p class="status"></p>

 </main>
 <?php
require "footer.php" 
 ?> 
<script type="text/javascript">
url = "./inc/social.inc.php?user="+<?=$_SESSION['userId']?>;

$.get(url, function(result) {

  if (result.following) {  
     result.following.forEach(user => {
     name = user.usersFirstname +" "+user.usersSecondname; 
    $("main").append(following_func(user.uidusers, name, user.idusers, user.profile_picture)); 
  }); 
  follow();
  }else if(!result.following){ 
      $('.status').text("you are following no one :(");   
  }   
}); 

function following_func(user, name, id, img) {
  if (img == null){
    img = 'M.jpg'; 
  }
  return `
<div class="follower-div">
 <img src="img/${img}" alt="this"> 
    <div class="user">
    <a href="profile.php?id=${id}"><h2>${name}</h2></a>   
    <p>${user}</p>
 </div> 
 <button id="${id}" class="btn follower-btn follow-btn">following</button>
  
  </div> `; 
};  


function follow() {
	 

$('.follow-btn').on('click', function () {  
        var key;
        /*---------------------improvise-------------*/  
        switch ($(this).text()) {
            case 'following':
            key = 'false';  
            break;
            case 'follow':
            key = 'true'; 
            break;
           default:   
            break;
        }
 
        if (key === 'true') { 
           $(this).text('following')
         }else{
           $(this).text('follow')         
         }

      url = "./inc/follow.inc.php?user="+<?=$_SESSION['userId']?>+"&following="+this.id+"&key="+key;    
       var settings = {
                 "async": true,
                "crossDomain": false,  
                "url": url,       
                "method": "GET",    
              }     
              
          $.ajax(settings).done(function (follow) {
            console.log(follow);   
        })
 })	;  
} 
 </script> 
