const _user_id = sessionStorage.getItem("user") || "";
const _user_name = sessionStorage.getItem("name") || "";

async function mainload(url = "./inc/post.inc.php?user=") {
  let post_no = 0;
  $("#image_post").on("change", function (e) {
    e.preventDefault();
    var m = URL.createObjectURL(event.target.files[0]);
    $("#type").val("img");
    $("#imagedisp").attr("src", m);
  });


  async function getUsers(url) {
    await $.get(url + _user_id, function (posts) {
      if (posts instanceof Array) {
        posts.forEach(async (post) => {
          if (!post.profile_picture) {
            post.profile_picture = "default.jpg";
          }
          await $("#main-post").append(render(post));
        });
        addClick();
        add_lightbox();
      } else if (posts instanceof Object) {
        if (posts?.type === 'error') return
        if (posts.data) {
          posts.data.forEach((post) => {
            if (!post.profile_picture) {
              post.profile_picture = "default.jpg";
            }
            $("#main-post").append(render(post));

          });

          post_no = posts.data.length;
        } else {
          if (!posts.profile_picture) {
            posts.profile_picture = "default.jpg";
          }
          $("#main-post").append(render(posts));
        }
        addClick();
        add_lightbox();
      } else {
        $("#main-post")
          .append(`<div class='post-div shadow no-user' class='text-center'><h4> you need to follow someone in order to view post on your feed</h4>
               <p> go to <a href="search.php?q=e"><b>search</b></a> and look for a user to follow</p>  
            </div>`);
      }
    });
  }
  await getUsers(url);
  return post_no;
}

function render(post) {
  // remove /n and /r from the string replace with space
  let post_text = post.image_text.replace(/\n/g, " ");
  let post_text_html = post_text.replace(/\r/g, " ");
  post.image_text = post_text_html;
  post.liked ? (l = "fas like") : (l = "far");
  post.user === true
    ? (_user = {
      name: _user_name,
      id: _user_id,
    })
    : (_user = post.user);
  if (post.type == "img") {
    return `
      <div class="post-div shadow">
      <div class="d-flex justify-content-between p-2 px-3">
      <div class="d-flex flex-row align-items-center">
        <img
          src="img/${post.profile_picture}"
          width="36px"
          height="36px"
          class="rounded-circle"
        />
        <div class="d-flex flex-column ml-2">
          <a href="profile.php?id=${_user.id}">
            <small class="text-left text-muted text-primary usn py-2 ml-1">@${_user.name
      }</small>
          </a>
        </div>
      </div>
      <div class="d-flex flex-row mt-1 ellipsis">
        <small class="mr-2 text-muted">${post.date_posted}</small>
        <a
          class="dropdown-toggle co"
          href="#"
          role="button"
          id="dropdownMenuLink"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="fa fa-ellipsis-h"></i
        ></a>
        <div
        class="dropdown-menu cob dropdown-menu-right shadow animated--fade-in bga"
        aria-labelledby="dropdownMenuLink"
        style=""
      >
        <div class="dropdown-header">Actions:</div>
        <a
          class="dropdown-item mj-actions post-page"
          href="post.php?id=${post.post_id}"
        >
          <i class="fa fa-eye fa-fw"></i> View Post
        </a>
        ${_user.name == _user_name ?
        `<a class="dropdown-item delete mj-actions" href="inc/post.inc.php?del_post=${post.post_id
        }">
          <i class="fa fa-trash fa-fw"></i> Delete Post
        </a>`: ''}
        <a
          class="dropdown-item mj-actions share"
          id="${post.post_id}"
          href="#share"
        >
          <i class="fa fa-share-alt fa-fw"></i> Share
        </a>
        <a class="dropdown-item mj-actions report" id="${post.id
      }" href="#report">
          <i class="fa fa-flag fa-fw"></i> Report
        </a>
        <a
          class="dropdown-item mj-actions  embed tocome"
          id="${post.post_id}"
          href="#embed"
        >
          <i class="fa fa-code fa-fw"></i> Embed
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="profile.php?id=${_user.id}"
          >visit profile</a
        >
      </div>
    </div>
    </div>
           <div class="lazyload">
           <!-- <a href="./img/${post.image
      }"  data-lightbox="${post.post_id}" data-title="${post.image_text}" data-image-alt="image post">     
                   <div class="post-body" style="background-image: url(./img/${post.image
      });">
                    </div> 
             </a> --> 
             </div>
          
              <p class="lone co p-1">${post.image_text}</p>
              <div class="social-opt">
              <div class="row social-act co w-100">
                <div class="col-3 flex icon">
                  <i title="like" tabindex="0" role="button"  id="${post.id}" class="${l} mr-1  this-click fa-heart"></i>
                   <small class="${post.id}">${post.post_likes <= 0 ? "" : post.post_likes}</small>
                </div>
                <div class="col-3 flex icon">
                  <a href="./comment.php?id=${post.post_id}">
                    <i title="comment" id="comment" class="fas mr-1  fa-comment comment "
                      ></i
                    ></a>
                   <small>${post.comments <= 0 ? "" : post.comments}</small>
                  
                </div>
                <div class="col-3 icon">
                  <a
                    href="#share"
                    class="share"
                    id="${post.id}"
                    ><i title="share this post" class="fas fa-share"></i
                  ></a>
                </div>
                <div class="col-3 icon">
                  <a
                    href="inc/repost.inc.php?id=${post.post_id}"
                    class="repost"
                    ><i title="repost this post" class="fas fa-retweet"></i
                  ></a>
                  </div>
              </div>
            </div>
        </div>
                 
 
         `;
  } else {
    return `
        <div class="post-div shadow">
        <div class="d-flex justify-content-between p-2 px-3">
        <div class="d-flex flex-row align-items-center">
          <img
            src="img/${post.profile_picture}"
            width="36px"
            height="36px"
            class="rounded-circle"
          />
          <div class="d-flex flex-column ml-2">
            <a href="profile.php?id=${_user.id}">
              <small class="text-left text-muted text-primary usn py-2 ml-1">@${_user.name
      }</small>
            </a>
          </div>
        </div>
        <div class="d-flex flex-row mt-1 ellipsis">
          <small class="mr-2 text-muted">${post.date_posted}</small>
          <a
            class="dropdown-toggle cob"
            href="#"
            role="button"
            id="dropdownMenuLink"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="fa fa-ellipsis-h"></i
          ></a>
          <div
          class="dropdown-menu cob dropdown-menu-right shadow animated--fade-in bga"
          aria-labelledby="dropdownMenuLink"
          style=""
        >
          <div class="dropdown-header">Actions:</div>
          <a
            class="dropdown-item mj-actions post-page"
            href="post.php?id=${post.post_id}"
          >
            <i class="fa fa-eye fa-fw"></i> View Post
          </a>
          ${_user.name == _user_name ?
        `<a class="dropdown-item delete mj-actions" href="inc/post.inc.php?del_post=${post.post_id
        }">
              <i class="fa fa-trash fa-fw"></i> Delete Post
            </a>`: ''}
          <a
            class="dropdown-item mj-actions share"
            id="${post.post_id}"
            href="#share"
          >
            <i class="fa fa-share-alt fa-fw"></i> Share
          </a>
          <a class="dropdown-item mj-actions report" id="${post.id
      }" href="#report">
            <i class="fa fa-flag fa-fw"></i> Report
          </a>
          <a
            class="dropdown-item mj-actions  embed tocome"
            id="${post.post_id}"
            href="#embed"
          >
            <i class="fa fa-code fa-fw"></i> Embed
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="profile.php?id=${_user.id}"
            >visit profile</a
          >
        </div>
      </div>
      </div>
            <div class="px-1 py-3">
            <p class="lone co px-2">${post.image_text}</p>
            </div>
            <div class="social-opt">
            <div class="row social-act co w-100">
              <div class="col-3 flex icon">
                <i title="like" tabindex="0" role="button" id="${post.id
      }" class="${l} mr-1  this-click fa-heart"
                  ></i
                >
                 <small class="${post.id}">${post.post_likes <= 0 ? "" : post.post_likes
      }</small>
              </div>
              <div class="col-3 flex icon">
                <a href="./comment.php?id=${post.post_id}">
                  <i title="comment" id="comment" class="fas mr-1  fa-comment comment "
                    ></i
                  ></a>
                 <small>${post.comments <= 0 ? "" : post.comments}</small>
                
              </div>
              <div class="col-3 icon">
                <a
                  href="#share"
                  class="share"
                  id="${post.post_id}"
                  ><i title="share this post" class="fas fa-share"></i
                ></a>
              </div>
              <div class="col-3 icon">
                <a
                  href="inc/repost.inc.php?id=${post.post_id}"
                  class="repost"
                  ><i title="repost this post" class="fas fa-retweet"></i
                ></a>
                </div>
            </div>
          </div>
        </div>
  `;
  }
}

function addClick() {
  $(".this-click").click(function (like) {
    let url = `./inc/like.inc.php?user=${_user_id}&id=${this.id}`;
    if (!this.classList.contains(`fas`)) {
      // increment likes
      let cl = `.${this.id}`;
      let likes = $(cl).text();
      likes++;
      $(cl).text(likes);
      url = url + "&like=" + likes + "&key=true";
      $(this).attr("class", `fas fa-heart  mr-1 icon-click like`);
      $.get(url, function (response) { });
    } else {
      // decrement likes
      let cl = `.${this.id}`;
      let likes = $(cl).text();
      likes--;
      $(cl).text(likes);
      $(this).attr("class", `far fa-heart  mr-1 icon-click like`);
      url = url + "&like=" + likes + "&key=false";
      $.get(url, function (response) { });
    }
  });
  $(".share").click(async function (e) {
    e.preventDefault;
    // share url from all pages should point to the post page
    let $a = window.location.pathname.split("/");
    if ($a.slice(-1)[0]) {
      $a.pop();
    }
    let url = `${$a.join("/")}/post.php?id=${this.id}`;
    url = window.location.origin + url;
    const shareData = {
      url: url,
    };

    try {
      await navigator.share(shareData);
      $.post("./inc/share.inc.php", {
        id: _user_id,
      });
    } catch (err) { }
  });
  $(".report").click(function (e) {
    e.preventDefault;
    $.post(
      "./inc/report.inc.php",
      {
        id: this.id,
      },
      function (data) {
        alert(data);
      }
    );
  });
  $(".tocome").click(function () {
    alert("I am still working on this...");
  });
  $(".lazyload").lazyload();
}

function profile_request(profile) {
  url = "./inc/profile.inc.php?id=" + profile + "&user=" + _user_id;
  $.get(url, function (user) {
    if (user.user) {
      let name = user.user.usersFirstname
        ? user.user.usersFirstname + " " + user.user.usersSecondname
        : "";
      $("#profile-name").text(name);
      $(".userName").text("@" + user.user.uidusers);
      $(".message-btn").attr("href", "message.php?id=" + user.user.chat_auth);
      $("#following").text(user.user.following);
      $("#followers").text(user.user.followers);
      $("#posts").text(user.user.no_posts);
      $(".bio").html(user.user.bio);
      if (user.user.profile_picture !== null) {
        $(".profile-pic").attr("src", "img/" + user.user.profile_picture);
      } else {
        $(".profile-pic").attr("src", "img/default.jpg");
      }

      if (user == user.user.uidusers) {
        $(".btn").hide();
      }
      follow();
      if (user.posts) {
        user.posts.forEach(async (post) => {
          if (!post.profile_picture) {
            post.profile_picture = "default.jpg";
          }
          await $("#main-post").append(render(post));
        });

        addClick();
      }
    } else {
      $(".bio").text("an unknown user");
      $(".btn").hide();
    }
  });
}

function post_request(profile) {
  url = "./inc/post.inc.php?id=" + profile;
  $.get(url, function (post) {
    post.profile_picture = post.user.profile_picture;
    if (!post.profile_picture) {
      post.profile_picture = "img/default.jpg";
    }
    $("#main-post").append(render(post));
    addClick();
  });
}



function get_popular_users() {
  let url = './inc/search.inc.php?type=users&query';
  $.get(url, function (post) {
    if (post.type == 'success') {
      post.data.forEach((item) => {
        let status = item.following ? 'following' : 'follow';
        $('#popular-users').append(`
        <li class="text-left  align-items-center p-2 border-0  justify-content-between"
        style='display: flex;'>
        <a href="profile.php?id=${item.token}">
        <span class='link co border-0'>@${item.uidusers}</span>
         </a>
        <button id='${item.token}' class=" bg p-1 text-center text-white  border-0  outline-0 follower-btn follow-btn p-0" 
        style='outline: none; border-radius: 5px; width: 40%; position: relative; overflow: hidden'>
        <span class="small">${status}</span>
        </button>
    </li>`)
      })

      follow(_user_id);

    } else {
      $('#popular-users').append("<div class='text-center w-full'>Not logged in</div>")
    }
  });
}

function get_popular_tags() {
  let url = './inc/search.inc.php?type=tags&query';
  $.get(url, function (tags) {
    if (tags.type == 'success') {
      tags.data.forEach((item) => {
        $("#popular-tags").append(`
        <a href="topics.php?t=${item.name}" class=" border-0 page-link tab_bg link">
          <li class="text-left align-items-center p-2 border-0  justify-content-between"
          style='display: flex;'>
            <span class='co border-0'>#${item.name}</span>
            <span class="small mx-2">view</span>    
          </li>
        </a>
    `);
      })
    } else {
      $('#popular-users').append("<div class='text-center w-full'>Not logged in</div>")
    }
  });
}
//report api
function report() { }
//follow api
function follow(user) {
  user = user || _user_id;
  $(".follow-btn").click(function () {
    profile = this.id || profile;
    var key;
    /*---------------------improvise-------------*/
    switch ($(this).children("span").text().trim()) {
      case "follow":
        key = "true";
        $(this).children("span").text("following");
        // add class to icon to indicate following
        $(this).children("i").attr("class", "fas fa-user  ml-2 no-h  text-white");
        // increment following count, parse it to int
        var following = parseInt($("#followers").text());
        following++;
        $("#followers").text(following);
        break;
      default:
        key = "false";
        $(this).children("span").text("follow");
        // remove class to icon to indicate following
        $(this).children("i").attr("class", "fas fa-user-plus ml-2 no-h  text-white ");
        // decrement following count, parse it to int
        var following = parseInt($("#followers").text());
        following--;
        $("#followers").text(following);
        break;
    }
    url =
      "./inc/follow.inc.php?user=" +
      user +
      "&following=" +
      profile +
      "&key=" +
      key;
    $.get(url, function (follow) {
      if (follow.type == "error") {
        alert(follow.message || follow.msg);
      }
    });
  });
}

function add_lightbox() {
  $("body").append(
    '<script defer src="./lib/lightbox/js/lightbox.min.js"></script>  '
  );
}


// for accessibility
$("body").on("keypress", "[tabindex='0']", function (e) {
  if (e.keyCode == 13 || e.keyCode == 32) {
    // 13=enter, 32=spacebar
    $(this).click();
    return false;
  }
})

$("#logout").click(function () {
  sessionStorage.clear();
});

function active_page(co) {
  let colors = ["purple", "pink", "yellow", "teal", "blue"];
  let active = colors[co];
  $(`.${active}`).addClass("active");
}
