<?php
require_once 'db.php';
$page_title = 'Home – Carthage Eagles';
$active_page = 'home';
include 'includes/header.php';
?>

  <!-- Hero -->
  <div class="hero">
    <picture>
      <source media="(max-aspect-ratio: 3/2)" srcset="img/BGCE-responsive.png" />
      <img class="plan-de-travail" src="img/BGCE.png" alt="Hero background" />
    </picture>
    <div class="hero-content">
      <button class="button"><div class="text-wrapper">HISTORY</div></button>
      <div class="arrow"><img class="img" src="img/arrow.svg" alt="" /></div>
    </div>
  </div>

  <!-- 1978 -->
  <div class="element history-section" data-reveal="left">
    <p class="element-first-world-cup">
      <span class="span">1978 &ndash; First World Cup<br /><br /></span>
      <span class="text-wrapper-2"
        >Participation in the 1978 FIFA World Cup in Argentina was a continental turning point. Tunisia became the
        first African team to win a World Cup match, defeating Mexico 3-1.<br />It wasn&rsquo;t just a victory: it was a
        geopolitical statement in football. Africa was no longer a bystander. This performance also contributed to
        increasing the number of African slots in future World Cups. Small step for Tunis, giant leap for CAF.</span
      >
    </p>
    <img class="element-2" src="img/1978-1.jpg" />
  </div>

  <!-- 1996 -->
  <div class="element-4 history-section" data-reveal="right">
    <img class="element-5" src="img/1996-1.jpg" />
    <p class="p">
      <span class="span">1996 &ndash; Africa Cup of Nations Finalist<br /><br /></span>
      <span class="text-wrapper-2"
        >After difficult years (missing six AFCON tournaments between 1978 and 1994), Tunisia came back strong and
        reached the final of the 1996 Africa Cup of Nations.<br />Even though they lost to South Africa, this final
        marked the beginning of a true resurgence. You could feel a cycle being prepared. The foundations were laid
        for something bigger.</span
      >
    </p>
  </div>

  <!-- 2004 -->
  <div class="div history-section" data-reveal="left">
    <p class="element-african">
      <span class="span">2004 &ndash; African Champions<br /><br /></span>
      <span class="text-wrapper-2"
        >The absolute high point: winning the 2004 Africa Cup of Nations, hosted at home.<br />Under the leadership
        of Roger Lemerre, Tunisia claimed its first and only continental title. Defeating Morocco 2-1 in the final
        firmly cemented this generation in national legend.<br />An entire country in a trance. Football became a
        collective story.</span
      >
    </p>
    <img class="element-2" src="img/2004-1.jpg" />
  </div>

  <!-- 2018 -->
  <div class="element-6 history-section" data-reveal="right">
    <img class="element-5" src="img/2018-1.jpg" />
    <p class="p">
      <span class="span">2018 &ndash; Return to the World Cup<br /><br /></span>
      <span class="text-wrapper-2"
        >Qualification for the 2018 FIFA World Cup ended 12 years of absence from the global stage.<br />Even if the
        journey was modest, this return symbolized regained stability and a new competitive generation. Tunisia
        became a regular actor in African football again.</span
      >
    </p>
  </div>

  <!-- 2022 -->
  <div class="element-3 history-section" data-reveal="left">
    <p class="element-victory-against">
      <span class="span">2022 &ndash; Victory against France<br /><br /></span>
      <span class="text-wrapper-2"
        >During the 2022 FIFA World Cup, Tunisia defeated the France national team 1-0 in the group stage.<br />Although
        they did not qualify for the round of 16, beating the reigning world champion was a moment of immense
        sporting pride. Football loves these paradoxes: you can exit early yet still write a memorable page.</span
      >
    </p>
    <img class="element-2" src="img/2022-1.jpg" />
  </div>

  <!-- 2026 Banner -->
  <div class="banner-2026">
    <h2 class="banner-2026__title">2026 &ndash; WE ARE AT THE WORLD CUP 🏆</h2>
    <p class="banner-2026__sub"><a href="book.php">BOOK YOUR SEAT &rarr;</a></p>
  </div>

  <!-- Scroll indicator dots -->
  <nav class="scroll-indicator" aria-label="Section navigation">
    <div class="scroll-indicator__track"></div>
    <button class="scroll-indicator__dot active" data-target="0" aria-label="1978"></button>
    <button class="scroll-indicator__dot" data-target="1" aria-label="1996"></button>
    <button class="scroll-indicator__dot" data-target="2" aria-label="2004"></button>
    <button class="scroll-indicator__dot" data-target="3" aria-label="2018"></button>
    <button class="scroll-indicator__dot" data-target="4" aria-label="2022"></button>
  </nav>


<?php 
$extra_js = 'script.js';
include 'includes/footer.php'; 
?>
