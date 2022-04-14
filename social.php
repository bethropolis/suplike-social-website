<?php
require "header.php";
$id = $_GET['id'] ?? '';

?>

<main>

  <p class="status text center"></p>

</main>
<?php
require "footer.php"
?>
<script type="text/javascript">
  let __user_id = '<?= $id ?>';
  if(__user_id){
    var user =  __user_id;
  }else{
    var user = sessionStorage.getItem('user'); 
  }

  let url = "./inc/social.inc.php?user=" + user;
  $.get(url, function(result) {
    if (result) {
      result.users.forEach(user => {
        name = user.usersFirstname + " " + user.usersSecondname;
        $("main").append(following_func(user.uidusers, name, user.idusers, user.profile_picture, user.token));
      });
      follow(sessionStorage.getItem('user'));
    } else if (!result.following) {
      $('.status').text("you are following no one :(");
    }
  });

  function following_func(user, name, id, img, token) {
    if (img === null) {
      img = 'M.jpg';
    }
    return `
<div class="follower-div">
 <img src="img/${img}" class="img-profile" onerror="this.error = null; this.src ='img/M.jpg' " alt="${user}">   
    <div class="user">
    <a href="profile.php?id=${token}"><h2>${name}</h2></a>
    <p>${user}</p>
 </div>
 <button id="${token}" class="btn  bg follower-btn follow-btn" style="border-radius:5px;">following</button>
  
  </div> `;
  };
</script>