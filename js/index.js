const _user_id = sessionStorage.getItem("user") || "";
const _user_name = sessionStorage.getItem("name") || "";

/**
 * Asynchronously loads posts from the specified user's url. Renders posts on the main-post div
 * and adds click and lightbox event listeners. Returns the number of posts rendered.
 *
 * @param {string} url='./inc/post.inc.php?user=' - The url to fetch posts from.
 * @return {number} The number of posts rendered.
 */
async function mainload(url = './inc/post.inc.php?user=') {
  let post_no = 0;

  function addDefaultProfilePicture(post) {
    if (!post.profile_picture) {
      post.profile_picture = 'default.jpg';
    }
  }

  function renderPosts(posts) {
    posts.forEach(async (post) => {
      addDefaultProfilePicture(post);
      await $('#main-post').append(render(post));
    });
  }

  function renderPost(post) {
    addDefaultProfilePicture(post);
    $('#main-post').append(render(post));
  }

  function renderErrorMessage() {
    $('#main-post').append(`<div class='post-div shadow no-user' class='text-center'><h4> you need to follow someone in order to view post on your feed</h4>
               <p> go to <a href='search.php?q=e'><b>search</b></a> and look for a user to follow</p>  
            </div>`);
  }

  function addClickAndLightbox() {
    addClick();
    add_lightbox();
  }

  $('#image_post').on('change', function (e) {
    e.preventDefault();
    const m = URL.createObjectURL(event.target.files[0]);
    $('#type').val('img');
    $('#imagedisp').attr('src', m);
  });

  async function getUsers(url) {
    const posts = await $.get(`${url}${_user_id}`);
    if (Array.isArray(posts)) {
      renderPosts(posts);
    } else if (posts?.type === 'error') {
      return;
    } else if (posts?.data) {
      renderPosts(posts.data);
      post_no = posts.data.length;
    } else {
      renderPost(posts);
    }
    addClickAndLightbox();
  }

  await getUsers(url);
  return post_no;
}

/**
 * Renders a post on the page with user information, post content, and social options.
 *
 * @param {object} post - The post object containing information like user, post type, likes, etc.
 * @return {string} The HTML string representing the post to be rendered on the page.
 */
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
  if (post.type == "img" || post.type == "txt") {
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
              <small class="text-left text-muted text-primary usn py-2 ml-1">@${_user.name}</small>
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
            <i class="fa fa-ellipsis-h"></i>
          </a>
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
        `<a class="dropdown-item delete mj-actions" href="inc/post.inc.php?del_post=${post.post_id}">
                <i class="fa fa-trash fa-fw"></i> Delete Post
              </a>` : ''}
            <a
              class="dropdown-item mj-actions share"
              id="${post.post_id}"
              href="#share"
            >
              <i class="fa fa-share-alt fa-fw"></i> Share
            </a>
            <a class="dropdown-item mj-actions report" id="${post.id}" href="#report">
              <i class="fa fa-flag fa-fw"></i> Report
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="profile.php?id=${_user.id}">visit profile</a>
          </div>
        </div>
      </div>
      ${post.type === "img" ?
        `
        <div class="lazyload">
          <!-- <a href="./img/${post.image}" data-lightbox="${post.post_id}" data-title="${post.image_text}" data-image-alt="image post">
            <div class="post-body" style="background-image: url(./img/${post.image});"></div>
          </a> -->
        </div>
        <p class="lone co p-1">${post.image_text}</p>
        ` :
        `
        <div class="px-1 py-3">
          <p class="lone co px-2">${post.image_text}</p>
        </div>
        `}
      <div class="social-opt">
        <div class="row social-act co w-100">
          <div class="col-3 flex icon">
            <i title="like" tabindex="0" role="button" id="${post.id}" class="${l} mr-1  this-click fa-heart"></i>
            <small class="${post.id}">${post.post_likes <= 0 ? "" : post.post_likes}</small>
          </div>
          <div class="col-3 flex icon">
            <a href="./comment.php?id=${post.post_id}">
              <i title="comment" id="comment" class="fas mr-1  fa-comment comment"></i>
            </a>
            <small>${post.comments <= 0 ? "" : post.comments}</small>
          </div>
          <div class="col-3 icon">
            <a href="#share" class="share" id="${post.post_id}">
              <i title="share this post" class="fas fa-share"></i>
            </a>
          </div>
          <div class="col-3 icon">
            <a href="inc/repost.inc.php?id=${post.post_id}" class="repost">
              <i title="repost this post" class="fas fa-retweet"></i>
            </a>
          </div>
        </div>
      </div>
    </div>`;
  }
  return
}

/**
 * Adds click event listeners to various elements on the page, such as like buttons, share buttons, report buttons, and lazy-loaded images.
 *
 * @param {type} none
 * @return {type} none
 */
function addClick() {

  $(".this-click").click(function (like) {
    const url = `./inc/like.inc.php?user=${_user_id}&id=${this.id}`;
    const cl = `.${this.id}`;
    const likes = Number($(cl).text());
    const likeIncrement = this.classList.contains(`fas`) ? -1 : 1;
    $(cl).text(likes + likeIncrement);
    const likeKey = likeIncrement === 1 ? "true" : "false";
    const likeClass = likeIncrement === 1 ? "fas fa-heart  mr-1 icon-click like" : "far fa-heart  mr-1 icon-click like";
    $(this).attr("class", likeClass);
    $.get(`${url}&like=${likes + likeIncrement}&key=${likeKey}`, function (response) { });
  });

  $(".share").click(async function (e) {
    e.preventDefault;
    const urlPath = window.location.pathname.split("/");
    if (urlPath[urlPath.length - 1]) {
      urlPath.pop();
    }
    let url = `${urlPath.join("/")}/post.php?id=${this.id}`;
    url = window.location.origin + url;
    const shareData = {
      url: url,
    };
    try {
      await navigator.share(shareData);
      $.post("./inc/share.inc.php", { id: _user_id });
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

/**
 * Asynchronously fetches a user profile by id and updates the profile page with the retrieved information.
 *
 * @param {string} profile - the id of the user profile to fetch
 * @return {Promise<void>} - a Promise that resolves when the profile information has been retrieved and the page has been updated
 */
async function profile_request(profile) {

  const url = `./inc/profile.inc.php?id=${profile}&user=${_user_id}`;
  const response = await fetch(url);
  const user = await response.json();

  if (user.user) {
    const { usersFirstname, usersSecondname, uidusers, chat_auth, following, followers, no_posts, bio, profile_picture, posts } = user.user;
    const name = usersFirstname ? `${usersFirstname} ${usersSecondname}` : "";

    $("#profile-name").text(name);
    $(".userName").text(`@${uidusers}`);
    $(".message-btn").attr("href", `message.php?id=${chat_auth}`);
    $("#following").text(following);
    $("#followers").text(followers);
    $("#posts").text(no_posts);
    $(".bio").html(bio);
    $(".profile-pic").attr("src", profile_picture !== null ? `img/${profile_picture}` : "img/default.jpg");
    $(".btn").toggle(user !== uidusers);
    follow();

    if (posts) {
      await Promise.all(posts.map(async (post) => {
        if (!post.profile_picture) {
          post.profile_picture = "default.jpg";
        }
        await $("#main-post").append(await render(post));
      }));

      addClick();
    }
  } else {
    $(".bio").text("an unknown user");
    $(".btn").hide();
  }
}

/**
 * Sends a POST request to the server to retrieve a post with the given `profile` id.
 *
 * @param {string} profile - The id of the profile to retrieve.
 * @return {void} This function does not return anything.
 */
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



/**
 * Retrieves popular users from the server and appends them to the DOM.
 *
 * @return {undefined} No return value.
 */
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

/**
 * Retrieves popular tags from the server and appends them to the popular-tags element.
 *
 * @return {void} No return value.
 */
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

/**
 * Attaches a click event listener to the ".follow-btn" element and sends a GET request to "./inc/follow.inc.php".
 *
 * @param {string} user_id - The ID of the user to follow. Defaults to a global variable "_user_id".
 * @return {void} This function does not return anything.
 */
function follow(user_id) {
  user_id = user_id || _user_id;
  $(".follow-btn").click(function () {
    var profile_id = this.id || profile_id;
    var is_following = $(this).children("span").text().trim() === "follow";
    var following_count_el = $("#followers");
    var following_count = parseInt(following_count_el.text());

    if (is_following) {
      $(this).children("span").text("following");
      $(this).children("i").attr("class", "fas fa-user  ml-2 no-h  text-white");
      following_count++;
    } else {
      $(this).children("span").text("follow");
      $(this).children("i").attr("class", "fas fa-user-plus ml-2 no-h  text-white ");
      following_count--;
    }

    following_count_el.text(following_count);

    var url = `./inc/follow.inc.php?user=${user_id}&following=${profile_id}&key=${is_following}`;
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
$("body").on("keydown", function (e) {
  if (e.keyCode === 13 || e.keyCode === 32) {
    var $target = $(e.target);
    if ($target.attr("tabindex") === "0") {
      $target.click();
      e.preventDefault();
    }
  }
});


$("#logout").click(function () {
  sessionStorage.clear();
});

function active_page(co) {
  let colors = ["purple", "pink", "yellow", "teal", "blue"];
  let active = colors[co];
  $(`.${active}`).addClass("active");
}
