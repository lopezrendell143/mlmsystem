<?php
// Simple autoloader to find the core layout infrastructure
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../includes/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require $file;
});

$page = new Layout("Syntrix - Intelligent Growth Platform", "home");
$page->renderHeader();
?>

<section class="hero-section d-flex flex-column justify-content-center align-items-center text-center px-3">
  <div class="container" style="max-width: 800px; z-index: 10;">
    <h1 class="display-4 fw-bold mb-3 tracking-tight text-white">
      Revolutionize Your Growth with <br class="d-none d-md-inline"> Our Intelligent Syntrix
    </h1>
    
    <p class="lead mb-4 mx-auto font-light" style="max-width: 600px; color: #cbd5e1 !important;">
      A complete, secure, and scalable platform designed for the modern networking professional.
    </p>

    <div class="pt-2">
      <a href="plans.php" class="btn btn-brand-green btn-lg px-5 py-3 rounded-3 shadow text-decoration-none">
        Get started
      </a>
    </div>
  </div>
</section>

<?php 
$page->renderFooter(); 
?>