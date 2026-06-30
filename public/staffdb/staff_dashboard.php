<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
    exit;
}

// Active page state for highlighting "Operations Hub" in the staff sidebar
$activePage = 'staff_dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - Syntrix</title>
  <!-- Bootstrap 5 & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    
    /* High-contrast metrics analytics sheets matching master dashboard specs */
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
    
    <!-- Dynamic Sidebar Component Injection -->
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <!-- Main Live Content Workplane Area -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <!-- Top Header Title bar -->
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom border-secondary">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">Operations Desk</h1>
          <p class="text-muted small mb-0">Monitor network support requests, process pending user profile verifications, and audit warehouse fulfillment.</p>
        </div>
      </div>

      <!-- Operational Metrics Row -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem;">Open Support Tickets</small>
            <h3 class="fw-bold mt-1 mb-0 text-info">14 Urgent</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem;">Pending KYC Reviews</small>
            <h3 class="fw-bold mt-1 mb-0">32 Profiles</h3>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-metric p-3 shadow-sm">
            <small class="text-muted text-uppercase tracking-wider fw-bold" style="font-size: 0.75rem;">Warehouse Packages</small>
            <h3 class="fw-bold mt-1 mb-0">124 Items Ready</h3>
          </div>
        </div>
      </div>

      <!-- Main Operational Queue Section (Upgraded from empty canvas box) -->
      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-activity me-2 text-info"></i>Active High-Priority Desk Actions</h5>
          <span class="badge bg-success-subtle text-success border border-success px-2 py-1 small">Systems Healthy</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">System Node</th>
                <th scope="col" class="py-3">Description Context</th>
                <th scope="col" class="py-3">Assigned Department</th>
                <th scope="col" class="py-3">Status</th>
                <th scope="col" class="py-3 text-end px-3">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-3 font-monospace text-info fw-bold">TCK-48195</td>
                <td>Binary volume synchronization lag reported by Affiliate user</td>
                <td><span class="text-white-50">Technical Helpdesk</span></td>
                <td><span class="badge bg-danger-subtle text-danger border border-danger px-2 py-0.5">HIGH</span></td>
                <td class="text-end px-3">
                  <a href="support_tickets.php" class="btn btn-sm btn-outline-info py-0.5" style="font-size: 0.75rem;">Review</a>
                </td>
              </tr>
              <tr>
                <td class="px-3 font-monospace text-info fw-bold">KYC-99214</td>
                <td>New identity verification documents awaiting registration approval</td>
                <td><span class="text-white-50">Member Verification</span></td>
                <td><span class="badge bg-warning-subtle text-warning border border-warning px-2 py-0.5">PENDING</span></td>
                <td class="text-end px-3">
                  <a href="member_verification.php" class="btn btn-sm btn-outline-info py-0.5" style="font-size: 0.75rem;">Review</a>
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