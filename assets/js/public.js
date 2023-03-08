const baseUrl = 'http://localhost/eveningclass101';
const mobileBreakpoint = 756;
const menuIcon = document.querySelector('.menu-icon');
const navOverlay = document.querySelector('header .nav-overlay');
const navMenu = document.querySelector('.navmenu');
const navItems = document.querySelectorAll('.navitem');

menuIcon.addEventListener('click', function (event) {
  navOverlay.classList.toggle('open');
  navMenu.classList.toggle('open');
});

navOverlay.addEventListener('click', function (event) {
  navOverlay.classList.remove('open');
  navMenu.classList.remove('open');
});

navItems.forEach(item => {
  const dropdown = item.querySelector('.dropdown');
  if (dropdown) {
    item.addEventListener('click', function (event) {
      item.classList.toggle('active');
    });
  }
});

// Switch between search icon and search form/input
const searchIcon = document.querySelector('.search-icon');
const headerSearchForm = document.querySelector('.header-search-form');
const headerSearchInput = document.querySelector('.search-input');
const logoWrapper = document.querySelector('.logo-wrapper');

function toggleSearchBar() {
  searchIcon.classList.toggle('hide');
  headerSearchForm.classList.toggle('hide');
  headerSearchInput.focus();
  if (window.innerWidth <= mobileBreakpoint) {
    logoWrapper.classList.toggle('hide');
  }
}

searchIcon.addEventListener('click', toggleSearchBar);
headerSearchInput.addEventListener('blur', toggleSearchBar);

const loadMoreButton = document.querySelector('.load-more-btn');

function displayPosts(posts) {
  let postsHtml = '<div>';
  posts.forEach(post => {
    postsHtml = `${postsHtml}
      <article class="post-card flat-card">
        <div class="image-wrapper bg-image" style="background-image: url(${baseUrl}/${post.post_image});"></div>
        <div class="post-info">
          <div class="topic-wrapper">
            <a href="${baseUrl}/topics/${post.topic_slug}" class="topic-tag">${post.topic_name}</a>
            <span class="read-time">${post.read_time}</span>
          </div>
          <div>
              <a href="${baseUrl}/posts/${post.slug}" class="td-none">
                <h3 class="post-title">${post.title}</h3>
              </a>
          </div>
          <div class="post-preview">
            <p>${post.body_preview}</p>
          </div>
          <div class="author-info">
            <div class="author">
                <img src="${baseUrl}/${post.user_image}" class="avatar" alt="">
                <a class="name simple-link" href="${baseUrl}/users/${post.username_slug}?id=${post.user_id}">
                ${post.username}
                </a>
            </div>
            <div>
            </div>
          </div>
        </div>
      </article>
    `;
  });
  postsHtml = `${postsHtml}</div>`;
  const morePostsNode = new DOMParser().parseFromString(postsHtml, "text/html");
  const postsNodeList = morePostsNode.firstChild.childNodes[1].childNodes;

  postsNodeList.forEach(postNode => {
    loadMoreButton.parentNode.insertBefore(postNode, loadMoreButton);
  });
}

async function fetchMorePosts(url) {
  loadMoreButton.innerHTML = 'Loading...';
  const response = await fetch(url);
  const posts = await response.json();
  displayPosts(posts);
  if (posts.length < 3) {
    const endElement = `<div style="text-align: center; color: gray;">You have reached the end.</div>`;
    const endNode = new DOMParser().parseFromString(endElement, "text/html")
    loadMoreButton.parentNode.replaceChild(endNode.firstChild.childNodes[1].childNodes[0], loadMoreButton);
  } else {
    loadMoreButton.innerHTML = 'Load more';
  }
}