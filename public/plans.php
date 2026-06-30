<?php
// Autoload standard class templates from infrastructure map
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../includes/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require $file;
});

$page = new Layout("Growth Plans - Syntrix", "plans");
$page->renderHeader();
?>

<section class="hero-section py-5 d-flex align-items-center">
  <div class="container text-center">
    <h1 class="display-5 fw-bold mb-5">Select your Growth Plan</h1>
    
    <div class="row g-4 justify-content-center align-items-stretch">
      <!-- Starter Plan -->
      <div class="col-md-4" style="max-width: 360px;">
        <div class="plan-card h-100 p-4 d-flex flex-column justify-content-between shadow-lg">
          <div>
            <h3 class="fw-bold tracking-wide mt-2">STARTER</h3>
            <h2 class="display-5 fw-bold my-3">$49<span class="fs-6 text-muted">/mo</span></h2>
            <hr>
            <ul class="list-unstyled text-secondary my-4 lh-lg" style="font-size: 0.85rem;">
              <li>10-Level Downline Support</li>
              <li>Basic Matching Bonus</li>
              <li>PV Tracking</li>
              <li>Admin Dashboard Access</li>
              <li>Support Ticket System</li>
            </ul>
          </div>
          <button class="btn btn-brand-green w-100 py-2.5 rounded-3 mt-3">Choose Starter</button>
        </div>
      </div>

      <!-- Growth Plan (Most Popular Layout Card) -->
      <div class="col-md-4" style="max-width: 360px;">
        <div class="plan-card popular h-100 d-flex flex-column justify-content-between shadow-lg position-relative">
          <div class="plan-card-header-green py-2 text-center text-uppercase tracking-wider small">
            Most Popular
          </div>
          <div class="p-4 flex-grow-1">
            <h3 class="fw-bold tracking-wide mt-2">GROWTH</h3>
            <h2 class="display-5 fw-bold my-3">$199<span class="fs-6 text-muted">/mo</span></h2>
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
            <button class="btn btn-brand-green w-100 py-2.5 rounded-3">Choose Growth</button>
          </div>
        </div>
      </div>

      <!-- Pro Plan -->
      <div class="col-md-4" style="max-width: 360px;">
        <div class="plan-card h-100 p-4 d-flex flex-column justify-content-between shadow-lg">
          <div>
            <h3 class="fw-bold tracking-wide mt-2">PRO</h3>
            <h2 class="display-5 fw-bold my-3">$499<span class="fs-6 text-muted">/mo</span></h2>
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
          <button class="btn btn-brand-green w-100 py-2.5 rounded-3 mt-3">Choose Pro</button>
        </div>
      </div>
    </div>
  </div>
</section>

<?php 
$page->renderFooter(); 
?>