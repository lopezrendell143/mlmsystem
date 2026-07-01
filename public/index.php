<?php
// Simple autoloader to find the core layout infrastructure
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../includes/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require $file;
});

$page = new Layout("Syntrix - Intelligent Growth Platform", "home");
$page->renderHeader();
?>

<style>
  html {
    scroll-behavior: smooth;
  }
  
  /* Offsets section landing anchors nicely underneath a sticky/fixed navbar */
  section.scroll-section {
    scroll-margin-top: 72px;
  }
</style>

<section id="home" class="scroll-section hero-section d-flex flex-column justify-content-center align-items-center text-center px-3">
  <div class="container" style="max-width: 800px; z-index: 10;">
    <h1 class="display-4 fw-bold mb-3 tracking-tight text-white">
      Revolutionize Your Growth with <br class="d-none d-md-inline"> Our Intelligent Syntrix
    </h1>
    
    <p class="lead mb-4 mx-auto font-light" style="max-width: 600px; color: #cbd5e1 !important;">
      A complete, secure, and scalable platform designed for the modern networking professional.
    </p>

    <div class="pt-2">
      <a href="#plans" class="btn btn-brand-green btn-lg px-5 py-3 rounded-3 shadow text-decoration-none">
        Get started
      </a>
    </div>
  </div>
</section>


<section id="about" class="scroll-section hero-section d-flex flex-column justify-content-center align-items-center text-center px-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
  <div class="container" style="max-width: 800px; z-index: 10;">
    <h1 class="display-4 fw-md mb-4 tracking-tight text-white">
      Our Story and Vision
    </h1>
    
    <p class="lead mb-4 mx-auto font-light" style="max-width: 680px; color: #cbd5e1 !important; line-height: 1.6;">
      A complete, secure, and scalable platform designed for the modern networking professional. 
      We are a team of tech innovators dedicated to empowering businesses.
    </p>

    <div class="pt-2">
      <button type="button" class="btn btn-brand-green btn-lg px-4 py-3 rounded-3 shadow" data-bs-toggle="modal" data-bs-target="#leadershipModal">
        Our Leadership
      </button>
    </div>
  </div>
</section>


<section id="plans" class="scroll-section hero-section py-5 d-flex align-items-center" style="border-top: 1px solid rgba(255,255,255,0.05);">
  <div class="container text-center">
    <h1 class="display-5 fw-bold mb-5 text-white">Select your Growth Plan</h1>
    
    <div class="row g-4 justify-content-center align-items-stretch text-start">
      <div class="col-md-4" style="max-width: 360px;">
        <div class="plan-card h-100 p-4 d-flex flex-column justify-content-between shadow-lg bg-white rounded-3">
          <div>
            <h3 class="fw-bold tracking-wide mt-2 text-dark">STARTER</h3>
            <h2 class="display-5 fw-bold my-3 text-dark">$49<span class="fs-6 text-muted fw-normal">/mo</span></h2>
            <hr>
            <ul class="list-unstyled text-secondary my-4 lh-lg" style="font-size: 0.85rem;">
              <li>10-Level Downline Support</li>
              <li>Basic Matching Bonus</li>
              <li>PV Tracking</li>
              <li>Admin Dashboard Access</li>
              <li>Support Ticket System</li>
            </ul>
          </div>
          <button class="btn btn-brand-green w-100 py-2.5 rounded-3 mt-3 text-dark fw-bold">Choose Starter</button>
        </div>
      </div>

      <div class="col-md-4" style="max-width: 360px;">
        <div class="plan-card popular h-100 d-flex flex-column justify-content-between shadow-lg position-relative bg-white rounded-3">
          <div class="plan-card-header-green py-2 text-center text-uppercase tracking-wider small rounded-top fw-bold text-dark" style="background-color: #10b981;">
            Most Popular
          </div>
          <div class="p-4 flex-grow-1">
            <h3 class="fw-bold tracking-wide mt-2 text-dark">GROWTH</h3>
            <h2 class="display-5 fw-bold my-3 text-dark">$199<span class="fs-6 text-muted fw-normal">/mo</span></h2>
            <hr>
            <ul class="list-unstyled text-secondary my-4 lh-lg" style="font-size: 0.85rem;">
              <li class="fw-bold text-dark mb-1">Starter Features plus...</li>
              <li>25-Level Downline Support</li>
              <li>Enhanced Pairing Bonus</li>
              <li>Legacy Override Tracker</li>
              <li>Inventory Monitoring</li>
              <li>Ranked Based Tiers</li>
            </ul>
          </div>
          <div class="p-4 pt-0">
            <button class="btn btn-brand-green w-100 py-2.5 rounded-3 text-dark fw-bold">Choose Growth</button>
          </div>
        </div>
      </div>

      <div class="col-md-4" style="max-width: 360px;">
        <div class="plan-card h-100 p-4 d-flex flex-column justify-content-between shadow-lg bg-white rounded-3">
          <div>
            <h3 class="fw-bold tracking-wide mt-2 text-dark">PRO</h3>
            <h2 class="display-5 fw-bold my-3 text-dark">$499<span class="fs-6 text-muted fw-normal">/mo</span></h2>
            <hr>
            <ul class="list-unstyled text-secondary my-4 lh-lg" style="font-size: 0.85rem;">
              <li class="fw-bold text-dark mb-1">Growth Features Plus...</li>
              <li>Unlimited Downline Support</li>
              <li>Max Matching & Pooling Bonuses</li>
              <li>KYC & Audit Logs</li>
              <li>Admin Role Customization</li>
              <li>Global Performance Analytics</li>
            </ul>
          </div>
          <button class="btn btn-brand-green w-100 py-2.5 rounded-3 mt-3 text-dark fw-bold">Choose Pro</button>
        </div>
      </div>
    </div>
  </div>
</section>


<section id="contact" class="scroll-section hero-section py-5 d-flex align-items-center" style="border-top: 1px solid rgba(255,255,255,0.05);">
  <div class="container">
    <h1 class="display-5 fw-bold text-center mb-5 text-white">Get in Touch with Our Team</h1>
    
    <div class="row g-4 justify-content-center align-items-stretch text-start">
      <div class="col-lg-6">
        <div class="form-container-white shadow-lg h-100 bg-white p-4 rounded-3">
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
            <button type="submit" class="btn btn-brand-green w-100 py-2.5 rounded-3 shadow fw-bold text-dark">Send Message</button>
          </form>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="form-container-white shadow-lg h-100 d-flex flex-column justify-content-between bg-white p-4 rounded-3">
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


<div class="modal fade" id="leadershipModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
    <div class="modal-content border-0 shadow-lg text-dark" style="border-radius: 16px;">
      <div class="modal-header border-0 pb-0 justify-content-end">
        <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center pt-0 px-4 pb-4">
        <h5 class="fw-bold mb-4">Meet Our Leadership Team</h5>
        
        <div class="mb-4">
          <div class="mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 80px; height: 80px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-circle text-secondary" viewBox="0 0 16 16">
              <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
              <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
          </div>
          <h6 class="fw-bold mb-0">Rendell Lopez</h6>
          <small class="text-muted text-uppercase tracking-wider fw-semibold" style="font-size:0.75rem;">CEO</small>
        </div>

        <div class="d-grid gap-2">
          <a href="tel:911" class="btn btn-success d-flex align-items-center justify-content-center gap-2 py-2.5 rounded-3 fw-medium text-white" style="background-color: #4cd04c; border:none;">
            Request a Call
          </a>
          <a href="mailto:rendell132004@gmail.com" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-2.5 rounded-3 fw-medium text-white" style="background-color: #2563eb; border:none;">
            Send a Direct Inquiry
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
$page->renderFooter(); 
?>