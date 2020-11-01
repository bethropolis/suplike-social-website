
<style>



.post-div{
    position: relative;
    padding: 0; 
    margin: 12px auto;    
    width: 90%;
    min-width: 220px;
    max-width: 350px; 
    height: auto;   
    background: #fff;  
    border: none;
    outline: none;
    box-shadow: 2px 2px 5px #333; 
    font-family: monospace;     
}

.post-head{
  display: flex;
  padding: 0;
  margin: 0; 
  width:100%;
  height: 38px;
  background: #f6f6f6;
  align-items: center;
}

.profile_picture{
  position: absolute;
  border: 2px dotted purple; 
  left: 6px; 
} 

.post_image{
	padding: 0;
	margin: 0;
    width: 100%; 
    height: auto;
    border-radius: 0;     
}
.social-opt{
  width:100%;
  height: 118px;
  background: #f6f6f6; 
}


.social-act{
  position: absolute;
  margin:4px; 
  width: 150px;  
  display: flex; 
  align-items: center;
  justify-content: space-between; 
}

.right{
  position: absolute; 
  right: 13px;
}

</style>  




<?php
echo '<link rel="stylesheet" href="../lib/font-awesome/font-awesome.css">';  
echo  '<link rel="stylesheet" href="../css/style.css">'; 
echo "<body>";
echo '<div class="post-div">';    
echo '<div class="post-head">';
echo '<img  class="profile_picture" src="http://localhost/files/php%20db%20test/img/logo.PNG" alt="image" style="width:30px; height: 30px;  border-radius: 50%;">';
echo '<i class="fa fa-ellipsis-h right  fa-2x"></i>';
echo '</div> ';
if (!$row['image'] == NULL){ 
echo '<img class="post_image" src="img/"'.$row['image'].'">';
echo "<p>".$row['image_text']."</p>";
}else{
  echo "<p class='lone-p'>".$row['image_text']."</p>";      
}
echo '<div class="social-opt">';
echo '<div class="social-act">';
echo '<i title="like" id="heart" class="fa fa-heart-o icon-click like fa-2x"></i>';
echo '<i title="comment" id="comment" class="fa fa-comment-o icon-click comment fa-2x"></i>';
echo '<i title="share it with your friends" id="share" class="fa fa-share fa-2x"></i>'; 
echo '</div>';
echo '</div> '; 
echo '</div>';  
 echo "</body>";
?> 

