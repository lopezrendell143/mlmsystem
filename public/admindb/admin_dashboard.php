<?php
session_start();

// Guard: Force admin access role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

// Active page state for highlighting "Dashboard" in your admin sidebar
$activePage = 'admin_dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Syntrix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    
    /* High-contrast metrics analytics sheets matching image_0382e5.jpg */
    .card-metric { background-color: #ffffff; color: #0f172a; border-radius: 12px; border: none; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    /* High-contrast dark operational tables */
    .table-custom { color: #ffffff; border-color: #1e293b; vertical-align: middle; }
    .table-custom thead { background-color: #0f172a; color: #94a3b8; }
    .table-custom tbody tr { border-bottom: 1px solid #1e293b; transition: background 0.15s; }
    .table-custom tbody tr:hover { background-color: rgba(30, 41, 59, 0.5); }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom border-secondary">
        <div>
          <h1 class="h2 fw-bold text-danger mb-0">Global Administration Panel</h1>
          <p class="text-muted small mb-0">Platform override and structural node relocation modules active.</p>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem;">Total Platform Revenue</small>
            <h3 class="fw-bold mt-1 mb-0">$1,240,500</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem;">Total Network Users</small>
            <h3 class="fw-bold mt-1 mb-0">14,250 Members</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem;">Pending Payout Queue</small>
            <h3 class="fw-bold mt-1 mb-0 text-danger">$18,400</h3>
          </div>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-sliders me-2 text-danger"></i>System-Wide Parameters & Gateways</h5>
          <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 small">[Secure Root Override Framework Online]</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">Configuration Parameter</th>
                <th scope="col" class="py-3">Current Active Multiplier</th>
                <th scope="col" class="py-3">Global Rule Status</th>
                <th scope="col" class="py-3 text-end px-3">Adjustment Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-3 fw-bold text-white">Direct Referral Commission Bonus</td>
                <td class="font-monospace text-info fw-bold">10.00%</td>
                <td><span class="badge bg-success-subtle text-success border border-success px-2 py-0.5">ACTIVE</span></td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-outline-danger py-0.5" style="font-size: 0.75rem;" onclick="alert('Opening gateway parameter alteration configuration grid...');">Modify</button>
                </td>
              </tr>
              <tr>
                <td class="px-3 fw-bold text-white">Binary Matching Pair Threshold</td>
                <td class="font-monospace text-info fw-bold">$100.00 base layer</td>
                <td><span class="badge bg-success-subtle text-success border border-success px-2 py-0.5">ACTIVE</span></td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-outline-danger py-0.5" style="font-size: 0.75rem;" onclick="alert('Opening gateway parameter alteration configuration grid...');">Modify</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>