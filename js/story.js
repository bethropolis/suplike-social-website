class SlideStories {
  constructor(id) {
    (this.slide = document.querySelector(`[data-slide=${id}]`)),
      (this.active = 0),
      (this.cont = document.querySelector(".cont")),
      (this.text = document.querySelector(".content-text")),
      (this.isHoldingStatus = false),
      this.init();
  }
  activeSlide(index) {
    (this.active = index),
      this.items.forEach((item) => item.classList.remove("active")),
      this.items[index].classList.add("active"),
      this.thumbItems.forEach((item) => item.classList.remove("active"));
    let text = this.items[index].alt || "";
    (this.text.innerHTML = text),
      this.thumbItems[index].classList.add("active"),
      this.autoSlide();
  }
  next() {
    this.active < this.items.length - 1
      ? this.activeSlide(this.active + 1)
      : (this.cont.style.display = "none");
  }
  prev() {
    this.active > 0
      ? this.activeSlide(this.active - 1)
      : this.activeSlide(this.items.length - 1);
  }
  addNavigation() {
    const nextBtn = this.slide.querySelector(".slide-next"),
      prevBtn = this.slide.querySelector(".slide-prev"),
      clo = document.querySelector("#stop_it");
    nextBtn.addEventListener("click", this.next),
      prevBtn.addEventListener("click", this.prev),
      clo.addEventListener("click", this.close.bind(this)),
      document
        .querySelector(".slide-items")
        .addEventListener("mousedown", () => {
          this.isHoldingStatus = true;
          clearTimeout(this.timeout);
        }),
      document.querySelector(".slide-items").addEventListener("mouseup", () => {
        this.isHoldingStatus = false;
        this.autoSlide();
      });
  }
  addThumbItems() {
    this.items.forEach(
      () => (this.thumb.innerHTML += '<span class="slide-thumb-item"></span>')
    ),
      (this.thumbItems = Array.from(this.thumb.children));
  }
  autoSlide() {
    clearTimeout(this.timeout),
      this.isHoldingStatus
        ? (this.timeout = setTimeout(() => {
            this.autoSlide();
          }, 1000))
        : (this.timeout = setTimeout(() => {
            this.next();
          }, 5000));
  }
  close() {
    clearTimeout(this.timeout);
    (close = document.querySelector(".cont")), (close.style.display = "none");
  }
  init() {
    (this.next = this.next.bind(this)),
      (this.prev = this.prev.bind(this)),
      (this.items = this.slide.querySelectorAll(".slide-items > *")),
      (this.thumb = this.slide.querySelector(".slide-thumbs")),
      this.addThumbItems(),
      this.activeSlide(0),
      this.addNavigation(),
      (this.cont.style.display = "flex");
  }
}
function renderStories() {
  let currentStoryIndex = 0;
  for (let user in user_data) {
    let div = document.createElement("div");
    div.classList.add("status-card"),
      (div.innerHTML = `\n            <div class="profile-pic"><img src="./img/${user_data[user].pic}" class="pic" alt="profile"></div>\n            <p class="username">${user_data[user].username}</p>\n            `),
      div.addEventListener("click", () => {
        slide_items.innerHTML = "";
        document.querySelector(".slide-thumbs").innerHTML = "";
        const stories = all_stories[user];
        const numStories = stories.length;

        for (let i = 0; i < numStories; i++) {
          const story = stories[i];
          if (story.type === "img") {
            slide_items.innerHTML += `<img src="./img/${story.image}" alt="${story.text}">`;
          } else {
            slide_items.innerHTML += `<p alt="${story.text}">${story.text}</p>`;
          }
        }

        roll = new SlideStories("slide");
        roll.activeSlide(currentStoryIndex);
        currentStoryIndex = (currentStoryIndex + 1) % numStories; // Move to the next story

        // Check if the current user has no more stories, move to the next user
        if (currentStoryIndex === 0) {
          const users = Object.keys(all_stories);
          const currentUserIndex = users.indexOf(user);
          const nextUserIndex = (currentUserIndex + 1) % users.length;
          const nextUser = users[nextUserIndex];
          currentStoryIndex = 0; // Reset the story index for the next user
          // Update the text and thumbnail for the next user
          content_text.innerHTML = user_data[nextUser].username;
          // Update the profile picture
          const profilePic = document.querySelector(".profile-pic img");
          profilePic.src = `./img/${user_data[nextUser].pic}`;
        }
      }),
      wrapper.appendChild(div);
  }
}
(all_stories = {}),
  (user_data = {}),
  (current = []),
  (content_text = document.querySelector(".content-text")),
  (slide_items = document.querySelector(".slide-items")),
  (wrapper = document.querySelector(".status-wrapper")),
  fetch(`./inc/stories.inc.php?user=${_user_id}`)
    .then((res) => res.json())
    .then((data) => {
      stories = data.stories;
      for (let user in stories) {
        (all_stories[user] = []), (user_data[user] = []);
        for (let story of stories[user])
          all_stories[user].push(story),
            (user_data[user].username = story.username),
            (user_data[user].pic = story.pic);
      }
      renderStories();
    }),
  (like_btn = document.querySelector("#like-btn")),
  like_btn.addEventListener("click", () => {
    like_btn.classList.contains("fas")
      ? like_btn.classList.replace("fas", "far")
      : like_btn.classList.replace("far", "fas");
  });
