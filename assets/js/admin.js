const baseUrl = 'http://localhost/eveningclass101';
// ===> Sidebar mobile responsiveness
const menuToggle = document.querySelector('.menu-icon');
const sidebar = document.querySelector('.sidebar');
const sidebarOverlay = document.querySelector('.sidebar-overlay');

menuToggle.addEventListener('click', function(event) {
  sidebar.classList.add('open');
  sidebarOverlay.classList.add('open');
});

sidebarOverlay.addEventListener('click', function() {
  sidebarOverlay.classList.remove('open');
  sidebar.classList.remove('open');
});

// ===> Function to display table pagination links
function displayPaginationLinks(wrapperElement, pageNumbers, currentPage) {
  wrapperElement.classList.remove('hide');
  let pageLinks = '';
  pageNumbers.forEach((page) => {
    if (page == currentPage) {
      pageLinks = `${pageLinks}<button href="?page=${page}" class="link active" disabled>${page}</button>`;
    } else if(page === '...') {
      pageLinks = `${pageLinks}<button href="?page=${page}" class="link" disabled>${page}</button>`;
    } else {
      pageLinks = `${pageLinks}<a href="?page=${page}" class="link">${page}</a>`;
    }
  });

  wrapperElement.innerHTML = `
    <td colspan="10">
      <div class="pagination-links">
        ${pageLinks}
      </div>
    </td>
  `;
}

// ===> A Storage class that simplifies the browser localStorage API
class Storage {
  static get(key) {
    return JSON.parse(localStorage.getItem(key));
  }
  static set(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
  }
  static remove(key) {
    localStorage.removeItem(key);
  }
}