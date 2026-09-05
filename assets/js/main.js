document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.querySelector('.nav-toggle');
  var links = document.querySelector('.nav-links');
  if (toggle && links) {
    toggle.addEventListener('click', function () {
      links.classList.toggle('open');
    });
  }

  var adminToggle = document.querySelector('.admin-toggle');
  var sidebar = document.querySelector('.admin-sidebar');
  if (adminToggle && sidebar) {
    adminToggle.addEventListener('click', function () {
      sidebar.classList.toggle('open');
    });
  }

  // auto-hide flash alerts
  document.querySelectorAll('.alert[data-autohide]').forEach(function (el) {
    setTimeout(function () { el.style.display = 'none'; }, 4000);
  });
});
