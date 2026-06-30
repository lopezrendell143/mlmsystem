<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php"); // Added ../ to go back to public/
    exit;
}

$activePage = 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Affiliate Dashboard - Syntrix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-metric { background-color: #ffffff; color: #0f172a; border-radius: 12px; border: none; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    .tree-box-canvas { background-color: #0d1e3d; border: 1px dashed #334155; border-radius: 12px; min-height: 300px; }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <!-- PLACED HERE: Dynamic Sidebar Component Injection -->
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <!-- Main Live Content Workplane Area -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <!-- Dashboard Top Summary Header -->
      <div class="row g-3 mb-4">
        <div class="col-lg-7">
          <div class="card-dark p-4 h-100">
            <h5 class="fw-bold mb-3 text-white">Network Performance Summary</h5>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary"><i class="bi bi-people-fill me-2"></i>Total Active Downline</span>
              <span class="fw-bold">24</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-secondary"><i class="bi bi-person-plus-fill me-2"></i>Direct Recruits</span>
              <span class="fw-bold">1</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-secondary"><i class="bi bi-patch-check me-2"></i>Rank Qualification %</span>
              <span class="fw-bold text-success">93%</span>
            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card-dark p-4 h-100">
            <h5 class="fw-bold mb-3 text-white">Critical Next Steps</h5>
            <ul class="list-unstyled mb-0 lh-lg text-secondary">
              <li><i class="bi bi-check2-circle text-success me-2"></i>Complete Rank Maintenance PV</li>
              <li><i class="bi bi-check2-circle text-success me-2"></i>Review Commission Qualification</li>
              <li><i class="bi bi-check2-circle text-success me-2"></i>Review Genealogy Balancing</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Financial Statistics Metrics Row -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold">Total GV (Group Volume)</small>
            <h4 class="fw-bold mt-1 mb-0">$25,000</h4>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold">Matched Leg Volume</small>
            <h4 class="fw-bold mt-1 mb-0">$12,800</h4>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold">E-Wallet Balance</small>
            <h4 class="fw-bold mt-1 mb-0 text-success">$3,450.00</h4>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold">Estimated Match Bonus</small>
            <h4 class="fw-bold mt-1 mb-0">$1,725.00</h4>
          </div>
        </div>
      </div>

      <!-- Live Genealogy Canvas Layout Frame -->
      <div class="row">
        <div class="col-12">
          <div class="p-4 shadow-sm card-dark rounded-3">
            <h5 class="fw-bold text-white mb-3">My Genealogy & Volumes</h5>
            <div class="tree-box-canvas d-flex align-items-center justify-content-center text-secondary text-center p-5">
              <div>
                <p class="mb-2 text-white-50">Hierarchical Binary Tree Renderer Engine Workspace Active</p>
                <small class="text-muted d-block">[Binary Placement Structure Leg Maps Online]</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>