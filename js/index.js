const _user_id = sessionStorage.getItem('user');
const _user_name = sessionStorage.getItem('name');

function mainload() {
    $('#image_post').on('change', function(e) {
        e.preventDefault();
        var m = URL.createObjectURL(event.target.files[0]); 
        $('#type').val('img'); 
        $('#imagedisp').attr("src", m); 
    });
    function getUsers() {
         $.get('./inc/post.inc.php?user=' + _user_id, function(posts) {
             if(posts  instanceof Array){    
            posts.forEach(post=>{
                $('#main-post').append(render(post));
     
            }
            )
          addClick();  
          }else{
            $('#main-post').append(`<div class='post-div shadow no-user' class='text-center'><h4> you need to follow someone in order to view post on your feed</h4>
               <p> go to <b>search</b> and look for a user to follow</p>  
            </div>`);  
          }
          

        })  
    }
 getUsers();
}

function render(post) {    
    post.liked ? l = "fa-heart like" : l = 'fa-heart-o';
    post.user === true ? _user = {
        name: _user_name,
        id: _user_id
    } : _user = post.user; 
    if (post.type == 'img') {
        return `
      <div class="post-div shadow">
            <div class="post-head  py-3 d-flex flex-row align-items-center justify-content-between">
                <a href="profile.php?id=${_user.id}"> 
                    <h5 class="text-left text-muted usn py-2 ml-1">@${_user.name}</h5>
                </a>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v fa-sm fa-fw text-dark"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" style="">
                        <div class="dropdown-header">Actions:</div>
                        <div class="mj-actions">
                        <a class="dropdown-item report" id="${post.id}" href="#">report <i class="fa fa-report fa-sm"></i></a>  
                       </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="profile.php?id=${_user.id}">visit profile</a>
                    </div>
                </div>
            </div>
              <img class="post_image my-3" loading="lazy" src="./img/${post.image}" /> 
              <p class="post-text py-1 mx-1">${post.image_text}</p>
            <div class="social-opt">
                <div class="social-act">
                    <i title="like" id="${post.id}" class="fa ${l} this-click  fa-2x">${post.post_likes}</i>
                    <a href="./comment.php?id=${post.post_id}"> <i title="comment" id="comment" class="fa fa-comment-o comment  fa-2x"></i></a>
                    <i title="share it with your friends" id="share" class="fa fa-share tocome fa-2x"></i>
                </div>
            </div>
        </div>
                 
 
         `
    } else {
        return `
        <div class="post-div shadow">
            <div class="post-head  py-3 d-flex flex-row align-items-center justify-content-between">
                <a href="profile.php?id=${_user.id}">
                    <h5 class="text-left text-muted usn py-2 ml-1">@${_user.name}</h5>
                </a>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v fa-sm fa-fw text-dark"></i>
                    </a>  
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" style="">
                        <div class="dropdown-header">Actions:</div>
                        <div class="mj-actions">
                        <a class="dropdown-item report" id="${post.id}" href="#">report <i class="fa fa-report fa-sm"></i></a>  
                       </div>  
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="profile.php?id=${_user.id}">visit profile</a>
                    </div>
                </div>
            </div>
            <div class="px-1 py-3">
            <p class="lone px-1">${post.image_text}</p>
            </div>
            <div class="social-opt">
                <div class="social-act">
                    <i title="like" id="${post.id}" class="fa ${l} this-click  fa-2x">${post.post_likes}</i>
                    <a href="./comment.php?id=${post.post_id}"> <i title="comment" id="comment" class="fa fa-comment-o comment  fa-2x"></i></a> 
                    <i title="share it with your friends" id="share" class="fa fa-share tocome fa-2x"></i>
                </div>
            </div>
        </div>
                 `
    }
}

function addClick() {
    $('.this-click').click(function(like) {
        let url = `./inc/like.inc.php?user=${_user_id}&id=${this.id}`;
        if (!this.classList.contains(`fa-heart`)) {
            let l = parseInt($(this).text()) + 1;
            $(this).text(l);
            url = url + '&like=' + l + "&key=true";
            $(this).attr('class', `fa fa-heart icon-click like fa-2x`);
            $.get(url, function(response) {
                console.log(response);
            })
        } else {
            $(this).attr('class', ` fa fa-heart-o icon-click fa-2x`);
            let l = parseInt($(this).text()) - 1;
            $(this).text(l);
            url = url + '&like=' + l + "&key=false";
            $.get(url, function(response) {
                console.log(response);
            })
        }
        ;
    });
    $('.report').click(function(e) {
        e.preventDefault;
        $.post('./inc/report.inc.php', {
            id: this.id
        }, function(data) {
            alert(data);
        });
    })
    $('.tocome').click(function() {
        alert("I am still working on this...")
    })
}

function profile_request(profile) {
    url = './inc/profile.inc.php?id=' + profile + '&user=' + _user_id;
    $.get(url, function(user) {     
        if (user.user) {        
            $('#profile-name').text(user.user.usersFirstname + ' ' + user.user.usersSecondname);
            $('.userName').text('@' + user.user.uidusers);
            $('.message-btn').attr('href', 'message.php?id=' + user.user.token); 
            $('#following').text('following: ' + user.user.following)
            $('#followers').text('followers: ' + user.user.followers)
            $('.bio').text(user.user.bio)
            if(user.user.profile_picture !== null){
                $(".profile-pic").attr('src', "img/"+user.user.profile_picture)  
            }
            
            if (user == user.user.uidusers) {
                $('.btn').hide();
            }
            follow(); 
            if (user.posts) {
                user.posts.forEach(async post=>{ 
                    await $('#main-post').append(render(post));

                }            
                )
                
              addClick();    
            }
        } else {
            $('.bio').text('an unknown user');
            $('.btn').hide();
        }

    })
}
;/*
@params hunt

*/
//report api
function report() {} 
//follow api 
function follow(user) {
    user = user || _user_id;
    $('.follow-btn').click(function() {
        profile = this.id || profile; 
        var key;
        /*---------------------improvise-------------*/
        switch ($(this).text()) {
        case 'follow':
            key = 'true';
            $(this).text('following');
            break;
        default:
            key = 'false';
            $(this).text('follow');
            break;
        }
        url = "./inc/follow.inc.php?user=" + user + "&following=" + profile + "&key=" + key;
        $.get(url, function(follow) {
        //     if(follow.type == 'error'){ 
        //     alert(follow.msg) 
        // }
        })
    })
}


$("#logout").click(function(){
    sessionStorage.clear(); 
})


