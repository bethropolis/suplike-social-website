<?php
require "header.php"; 
if (!isset($_SESSION['userId'])) {
  header('Location: ./login.php');
  exit();    
}  

 
 
?>

 <main> 
 <!--  <div class="loader">
    <progress-ring stroke="4" radius="60" progress="0"></progress-ring>
  </div> -->
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
 


</style>

<div class="row"> 
<div class="col-sm-3 sidebar-sticky pt-3">             
 	<div class="card card-profile text-center profile-card sidebar-content sticky-top" style="width: 94%;" >    
 	    <a href="./profile.php">         
 	     <img class="profile-pic shadow-sm"<?php if(!is_null($_SESSION['profile-pic'])){  echo 'src="img/'.$_SESSION['profile-pic'].'"';}else{ echo 'src="img/M.jpg"';}  ?> title=" " alt="profile picture" style="width: 70px;height: 70px; border-radius: 50%;">
 	      </a>   
 	      <h4 id="profile-name"><?php echo $_SESSION['firstname']; echo " ".$_SESSION['lastname'];?></h4>  
 	      <h5 class="text-center userName"><?php echo "@".$_SESSION['userUid']?></h5>    
          <ul class="profile-opt">       
          	<a href="profile.php"  style="color: #252130;"><i class="fa fa-user fa-2x"></i></a>    
          	<a href="#textinput" style="color: #252130;"style="color: #252130;"><i class="fa fa-edit fa-2x"></i></a>  
          	<a href="settings.php" style="color: #252130;"><i class="fa fa-cog fa-2x"></i></a>     
          </ul> 
 	</div>  
 </div>

<!-- -------------------------------------------this is the posting area --------------------------------------------------------------->
 
 <div class="col-sm-7">    

  <div class="container-lg" >    
  	<form action="inc/post.inc.php" class="form  postform" style="padding: .7em;"  method="POST" enctype="multipart/form-data">  
      <img src="" alt="">
  	 <input type="file" name="image" id="image_post"  style="display: none;"> 
  	   <input type="text" id="time_of_post" name="time_of_post" style="display: none;" readonly>   
  	    <input type="text" id="time_posted" name="time_posted" style="display: none;" readonly>      
  	 <textarea id="text"cols="40" class="textinput" rows="4" name="posttext" placeholder="what would you like to post about.." oninvalid="this.setCustomValidity('Please you have to write something about this, text cannnot be empty')"  oninput="setCustomValidity('')" required></textarea>   
  	  <div class=" ml-auto mr-1 post-options">
  	   <label for="image_post"><i class="fa fa-image fa-2x"></i></label>      
  	  </div>
  	 <button class="btn post-btn" name="upload">post</button>    
   </form>
  </div >
     <div> 
    <?php
     require 'inc/post.inc.php';  
      while ($row = mysqli_fetch_array($result)) {  
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
     
     }  
   ?> 
   </div> 

 </div>
<!--  ----------------------------------------------------------------this is the 3rd view or something-------------------------------------- -->
 <div class="col-sm-2" >
   <i class="fa fa-spinner"></i> 
 </div>
</div> 


 </main>
<?php
require "footer.php"
 ?> 

 <!--------- main script----->
 <script>
 	var l = document.getElementById('time_of_post')
 	var y = document.getElementById('time_posted')
 	    $(document).ready(function(){ 
         var d = new Date;
         var month = ['jan','feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] 
         var h = d.getDate();   
         var m = month[d.getMonth()];    
         d = Date.parse(d);      
         let w = h + " "+ m;  
          l.value = d;
          y.value = w;

         $('#image_post').on('change', function (e) {     
          e.preventDefault();
          console.log(event.target.files)
         var m = URL.createObjectURL(event.target.files[0]);
         $('#image_post').attr("src", m); 

         });       
          $('.this-click').click(function(like){ 
                   
          if (!this.classList.contains(`fa-heart`)){ 

          var q ;  
          var l = $(this).text() ;
           l++ ; 
           $(this).text(l);
           var url ='<?php echo "./inc/like.inc.php?user=".$_SESSION['userId'];?>'; 
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
                var url ='<?php echo "./inc/like.inc.php?user=".$_SESSION['userId'];?>';   
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
 	  });  


 </script> 