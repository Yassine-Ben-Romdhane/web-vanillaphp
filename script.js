// Hamburger menu toggle
document.querySelector('.hamburger').addEventListener('click', function () {
  document.querySelector('.navbar-2').classList.toggle('active');
});

// Smooth scroll past hero on HISTORY button click
document.querySelector('.button').addEventListener('click', function () {
  var hero = document.querySelector('.hero');
  var target = hero.offsetTop + hero.offsetHeight;
  window.scrollTo({ top: target, behavior: 'smooth' });
});

// Scroll-reveal for history sections
var observer = new IntersectionObserver(function (entries) {
  entries.forEach(function (entry) {
    if (entry.isIntersecting) {
      var dir = entry.target.getAttribute('data-reveal');
      entry.target.classList.add(dir === 'right' ? 'reveal-right' : 'reveal-left');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('.history-section').forEach(function (el) {
  observer.observe(el);
});

// Scroll indicator logic
(function () {
  var sections = document.querySelectorAll('.history-section');
  var dots = document.querySelectorAll('.scroll-indicator__dot');
  var indicator = document.querySelector('.scroll-indicator');
  var hero = document.querySelector('.hero');

  // Click a dot → scroll to that section
  dots.forEach(function (dot) {
    dot.addEventListener('click', function () {
      var idx = parseInt(this.getAttribute('data-target'));
      sections[idx].scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
  });

  // Update active dot + show/hide indicator on scroll
  function updateIndicator() {
    var heroBottom = hero.offsetTop + hero.offsetHeight;
    var scrollY = window.scrollY || window.pageYOffset;

    // Hide indicator when in hero section
    if (scrollY < heroBottom - 200) {
      indicator.classList.remove('visible');
      return;
    }
    indicator.classList.add('visible');

    var activeIdx = 0;
    sections.forEach(function (sec, i) {
      var rect = sec.getBoundingClientRect();
      if (rect.top < window.innerHeight * 0.5) {
        activeIdx = i;
      }
    });

    dots.forEach(function (d, i) {
      d.classList.toggle('active', i === activeIdx);
    });
  }

  window.addEventListener('scroll', updateIndicator, { passive: true });
  updateIndicator();
})();
