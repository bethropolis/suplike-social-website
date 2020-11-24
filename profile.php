<?php
require 'header.php';
require 'inc/dbh.inc.php';   

if (!isset($_SESSION['userId'])) {
  header('Location: ./login.php');
  exit();    
}  


$profile = isset($_GET['id'])? $_GET['id']: $_SESSION['userId'];
$follow = 'follow';
$query = "SELECT * FROM `following` WHERE user=".$_SESSION['userId']." AND `following`='$profile'"; 
$result = $conn->query($query)->fetch_assoc(); 
if (!is_null($result)) {
   $follow = 'following';
 } 
?> 
<style type="text/css"> 
    .post-div{
    position: relative; 
    padding: 0; 
    margin: 12px auto;    
    width: 90%;
    min-width: 220px;
    max-width: 550px;   
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
  height: 58px; 
  background: var(--white);  
}
.post-text{
  margin-left: 19px;    
  text-align: left; 
  font-size: 18px;  
  font-weight: 700;  
  font-family: "Rockwell Extra Bold", "Rockwell Bold", monospace;   
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
.like{
  color: var(--ho);   
}
.social-status{
  list-style: none;
  display: flex; 
  align-items: center; 
  justify-content: space-around; 
}
 </style>

 
<div class="col-sm-12 sidebar-sticky pt-3" style="max-width: 440px; margin: 0 auto">              
 	<div class="card card-profile text-center profile-card sidebar-content sticky-top" style="width: 94%;" >    
 	    <a href="#">         
 	     <img class="profile-pic shadow-sm"<?php if(!is_null($_SESSION['profile-pic'])){  echo 'src="img/'.$_SESSION['profile-pic'].'"';}else{ echo 'src="img/M.jpg"';}  ?> title=" " alt="profile picture" style="width: 70px;height: 70px; border-radius: 50%;">
 	      </a>  
 	      <h4 id="profile-name"></h4> 
 	      <h5 class="text-center userName"></h5>
        <p class="bio"></p>
        <ul class="social-status">
          <li id="following">following:</li>
          <li id="followers">followers:</li>  
        </ul>     
          <ul class="profile-opt"> 
          	<?php if (!($_SESSION['userId'] == $profile)){  
          	   echo '<a href="" class="message-btn"><button class="btn">message</button></a> 
              <button class="btn follow-btn">'.$follow.'</button>';           
 
          	} ?>           	          
          </ul>
 	</div> 
 </div>

<h3 style="margin: 18px;">posts</h3>   



<?php   
     $result = mysqli_query($conn, "SELECT * FROM `posts` WHERE `userid`='$profile' ORDER BY `posts`.`id` DESC");   
      while ($row = mysqli_fetch_array($result)) {  
      echo "<body>";
      echo '<div class="post-div">';    
      echo '<div class="post-head">';
      echo '<img  class="profile_picture" src="http://localhost/files/php%20db%20test/img/logo.PNG" alt="image" style="width:30px; height: 30px;  border-radius: 50%;">';
      echo '<i class="fa fa-ellipsis-h right  fa-2x"></i>';
      echo '</div> ';
      if (!empty($row['image'])){   
      echo '<img class="post_image my-3" src="img/'.$row['date_of_upload'].'">';  
      echo "<p class='post-text'>".$row['image_text']."</p>";
      }else{
      echo "<p class='lone-p'>".$row['image_text']."</p>";      
      }
      echo '<div class="social-opt">';
      echo '<div class="social-act">'; 
      $result2 = $conn->query("SELECT * FROM `likes` WHERE `user_id`=".$_SESSION['userId']." AND `post_id`=".$row['id']."")->fetch_assoc();  
      if (is_null($result2)){
        echo '<i title="like" id="'.$row['id'].'" class="fa fa-heart-o this-click  fa-2x">'.$row['post_likes'].'</i>';   
      }else{ 
        echo '<i title="like" id="'.$row['id'].'" class="fa fa-heart this-click like fa-2x">'.$row['post_likes'].'</i>';  
      }  
      echo '<i title="comment" id="comment" class="fa fa-comment-o comment fa-2x"></i>';
      echo '<i title="share it with your friends" id="share" class="fa fa-share fa-2x"></i>'; 
      echo '</div>';
      echo '</div> '; 
      echo '</div>';  
      echo "</body>";
     }  

     require_once 'footer.php'; 
   ?>  

   <script>  

      profile_request(); 
      function profile_request(){
       url = './inc/profile.inc.php?id='+<?=$profile?>+'';  
              var settings = {
                 "async": true,
                "crossDomain": false,  
                "url": url,       
                "method": "GET",    
              }     
              
            $.ajax(settings).done(function (user) {
             $('#profile-name').text(user.user.usersFirstname+' '+user.user.usersSecondname); 
             $('.userName').text('@'+user.user.uidusers); 
             $('.message-btn').attr('href','message.php?id='+user.user.idusers);
             $('#following').text('following: '+user.user.following)
             $('#followers').text('followers: '+user.user.followers)
             $('.bio').text(user.user.bio)        
         }) 
        };  

//follow api 
     $('.follow-btn').click(function () {   
        var key;
        /*---------------------improvise-------------*/ 
        if ($('.follow-btn').text() == 'follow') {
            key = 'true';            
        }
        if ($('.follow-btn').text() == 'following') { 
            key = 'false';   
        } 
        if (key === 'true') { 
           $('.follow-btn').text('following')
         }else{
           $('.follow-btn').text('follow')         
         }

      url = "./inc/follow.inc.php?user="+<?=$_SESSION['userId']?>+"&following="+<?=$profile?>+"&key="+key;
      console.log(url) 
       var settings = {
                 "async": true,
                "crossDomain": false,  
                "url": url,       
                "method": "GET",    
              }     
              
          $.ajax(settings).done(function (follow) {
            console.log(follow);   
        })
     })





//  like api  
   	  $('.this-click').click(function(like){ 

          if (!this.classList.contains(`fa-heart`)){ 

          var q ;  
          var l = $(this).text() ;
           l++ ; 
           $(this).text(l);
           var url ='<?php echo "http://localhost/files/php%20db%20test/inc/like.inc.php?user=".$_SESSION['userId'];?>'; 
           url = url+"&id="+this.id+'&like='+l+"&key=true";     
                
          $(this).attr('class', `fa fa-heart icon-click like fa-2x`);
            send_request();    
           function send_request(){   
                  var settings = {
	               "async": true,
               	"crossDomain": false,  
              	"url": url,      
	              "method": "GET",   
              }     
              
         $.ajax(settings).done(function (response) {
              console.log(response); 
               
         }) 
               
        }; 

               
          } else{   
          $(this).attr('class', ` fa fa-heart-o icon-click fa-2x`);  
          var l = $(this).text() ;
           l-- ;  
           $(this).text(l);    
                var url ='<?php echo "http://localhost/files/php%20db%20test/inc/like.inc.php?user=".$_SESSION['userId'];?>';   
                url = url+"&id="+this.id+'&like='+l+"&key=false"; 
         
            send_request(); 
           function send_request(){  
                  var settings = {
                 "async": true,
                "crossDomain": false,  
                "url": url,      
                "method": "GET",    
              }     
              
            $.ajax(settings).done(function (response) {
              console.log(response);                              
         })
        };  
         }
      });
   </script>