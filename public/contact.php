<?php
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../includes/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require $file;
});

$page = new Layout("Get in Touch - Syntrix", "contact");
$page->renderHeader();
?>

<section class="hero-section py-5 d-flex align-items-center">
  <div class="container">
    <h1 class="display-5 fw-bold text-center mb-5">Get in Touch with Our Team</h1>
    
    <div class="row g-4 justify-content-center align-items-stretch">
      <!-- Contact Form Block Container -->
      <div class="col-lg-6">
        <div class="form-container-white shadow-lg h-100">
          <form action="" method="POST">
            <div class="mb-3">
              <label class="form-label fw-bold text-secondary" style="font-size: 0.9rem;">Name</label>
              <input type="text" class="form-control bg-light border-0 py-2" style="border-radius: 8px;" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold text-secondary" style="font-size: 0.9rem;">Email</label>
              <input type="email" class="form-control bg-light border-0 py-2" style="border-radius: 8px;" required>
            </div>
            <div class="mb-4">
              <label class="form-label fw-bold text-secondary" style="font-size: 0.9rem;">Message</label>
              <textarea class="form-control bg-light border-0" rows="5" style="border-radius: 8px;" required></textarea>
            </div>
            <button type="submit" class="btn btn-brand-green w-100 py-2.5 rounded-3 shadow fw-bold">Send Message</button>
          </form>
        </div>
      </div>

      <!-- Right Support Information Context Panel -->
      <div class="col-lg-4">
        <div class="form-container-white shadow-lg h-100 d-flex flex-column justify-content-between">
          <div>
            <h5 class="fw-bold text-muted mb-4 text-uppercase tracking-wider" style="font-size:0.85rem;">Alternative Contact Info</h5>
            
            <div class="mb-4">
              <small class="text-secondary d-block mb-1">Email Us</small>
              <span class="fw-bold fs-5 text-dark">rendell132004@gmail.com</span>
            </div>
            
            <div class="mb-4">
              <small class="text-secondary d-block mb-1">Call Us</small>
              <span class="fw-bold fs-5 text-dark">911</span>
            </div>
          </div>

          <div class="pt-4" style="border-top: 1px solid #e2e8f0;">
            <small class="text-secondary d-block mb-1">Visit Us</small>
            <span class="fw-bold fs-6 text-dark">Sto. Tomas Batangas</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php 
$page->renderFooter(); 
?>