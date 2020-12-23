<?php
require "header.php";
?>

 <main>

 <p class="status text center"></p>

 </main>
 <?php
require "footer.php" 
 ?> 
<script type="text/javascript">
const user = sessionStorage.getItem('user'); 
let url = "./inc/social.inc.php?user="+user;
$.get(url, function(result) {
  if (result) {    
     result.forEach(user => {
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
 <button id="${id}" class="btn  bg follower-btn follow-btn">following</button>
  
  </div> `; 
};  

 </script> 
