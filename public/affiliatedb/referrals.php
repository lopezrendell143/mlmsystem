<?php
session_start();

// Guard: Force affiliate access role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// Set active page flag so the sidebar highlights "My Referrals" correctly
$activePage = 'referrals'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Referrals - Syntrix</title>
  <!-- Bootstrap 5 & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    /* High-contrast table styles matching dashboard overview sheets */
    .table-custom { color: #ffffff; border-color: #1e293b; vertical-align: middle; }
    .table-custom thead { background-color: #0f172a; color: #94a3b8; }
    .table-custom tbody tr { border-bottom: 1px solid #1e293b; transition: background 0.15s; }
    .table-custom tbody tr:hover { background-color: rgba(30, 41, 59, 0.5); }
    
    .referral-link-box {
      background-color: #0f172a;
      border: 1px dashed #334155;
      border-radius: 8px;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <!-- Dynamic Sidebar Component Injection -->
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <!-- Main Content Workplane -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <!-- Top Title Bar Headers -->
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
        <div>
          <h1 class="h3 fw-bold mb-0 text-white">Direct Recruits</h1>
          <p class="text-muted small mb-0">Track frontline sponsorships, manage link configurations, and audit team volume contributions.</p>
        </div>
      </div>

      <!-- Referral Link Sharing Hub Card -->
      <div class="card-dark p-4 mb-4 shadow-lg">
        <h5 class="fw-bold mb-3 text-white-50"><i class="bi bi-link-45deg me-2 text-success"></i>Sponsorship Node Invitation Link</h5>
        <div class="referral-link-box p-3 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
          <div class="d-flex align-items-center gap-3 w-100">
            <i class="bi bi-globe fs-4 text-primary"></i>
            <span class="font-monospace text-info small text-break" id="refLink">http://localhost/mlmsystem/public/register.php?ref=STX-00134</span>
          </div>
          <button class="btn btn-sm btn-brand-green text-dark fw-bold px-4 text-nowrap" onclick="navigator.clipboard.writeText(document.getElementById('refLink').innerText); alert('Sponsorship node link copied to clipboard!');">
            <i class="bi bi-clipboard me-2"></i>Copy Link
          </button>
        </div>
      </div>

      <!-- Main High-Contrast Referrals Table Data Layout Grid -->
      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-people-fill me-2 text-success"></i>Direct Sponsorship Network</h5>
          <span class="badge bg-success-subtle text-success border border-success px-3 py-1.5 small">1 Active Frontline Partner</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">Member ID</th>
                <th scope="col" class="py-3">Full Name</th>
                <th scope="col" class="py-3">Email Status</th>
                <th scope="col" class="py-3">Binary Placement</th>
                <th scope="col" class="py-3">Personal Volume</th>
                <th scope="col" class="py-3">Enrollment Date</th>
                <th scope="col" class="py-3 text-end px-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <!-- Active Row Data Node -->
              <tr>
                <td class="px-3 font-monospace text-success fw-bold">STX-00241</td>
                <td>
                  <div class="fw-bold">Alex Mercer</div>
                  <span class="text-muted" style="font-size: 0.7rem;">Rank: Associate Partner</span>
                </td>
                <td><span class="text-light small">alex.mercer@gmail.com</span></td>
                <td><span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1" style="font-size: 0.7rem;">LEFT LEG</span></td>
                <td class="fw-bold text-info">450 PV</td>
                <td class="text-muted small">2026-06-14</td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-outline-light py-1 text-white" style="font-size: 0.75rem;" onclick="alert('Viewing volume detail node context...');">View Metrics</button>
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