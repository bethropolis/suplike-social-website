class SlideStories {
  constructor(id) {
    (this.slide = document.querySelector(`[data-slide=${id}]`)),
      (this.active = 0),
      (this.cont = document.querySelector(".cont")),
      (this.text = document.querySelector(".content-text")),
      (this.isHoldingStatus = !1),
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
      clo.addEventListener("click", this.close),
      document
        .querySelector(".slide-items")
        .addEventListener("mousedown", () => {
          this.isHoldingStatus = !0;
        }),
      document.querySelector(".slide-items").addEventListener("mouseup", () => {
        this.isHoldingStatus = !1;
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
          }, 1e3))
        : (this.timeout = setTimeout(() => {
            this.next();
          }, 5e3));
  }
  close() {
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
  for (let user in user_data) {
    let div = document.createElement("div");
    div.classList.add("status-card"),
      (div.innerHTML = `\n            <div class="profile-pic"><img src="./img/${user_data[user].pic}" class="pic" alt="profile"></div>\n            <p class="username">${user_data[user].username}</p>\n            `),
      div.addEventListener("click", () => {
        (slide_items.innerHTML = ""),
          (document.querySelector(".slide-thumbs").innerHTML = ""),
          all_stories[user].forEach((story) => {
            "img" == story.type
              ? (slide_items.innerHTML += `\n                    <img src="./img/${story.image}" alt="${story.text}">\n                    `)
              : (slide_items.innerHTML += `\n                    <p alt="${story.text}">${story.text}</p>\n                    `);
          }),
          (roll = new SlideStories("slide"));
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
