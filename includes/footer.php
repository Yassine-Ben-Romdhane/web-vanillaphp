      <!-- Footer -->
      <div class="frame">
        <div class="frame-2">
          <div class="text-wrapper-3">ABOUT</div>
          <div class="text-wrapper-3">CONTACT</div>
        </div>
      </div>
    </div> <!-- Close main div from header or page content -->

    <script>
      // Common Hamburger Toggle
      var hamburger = document.getElementById('hamburger');
      var navMenu = document.getElementById('navMenu');
      if (hamburger && navMenu) {
        hamburger.addEventListener('click', function () {
          navMenu.classList.toggle('active');
        });
      }
    </script>
    <?php if (isset($extra_js)): ?>
    <script src="<?php echo $extra_js; ?>"></script>
    <?php endif; ?>
  </body>
</html>
