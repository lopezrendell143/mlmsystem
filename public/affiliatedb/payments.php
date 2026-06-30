<?php
session_start();

// Guard: Force affiliate access role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// Set active page flag so the sidebar highlights "Payments" correctly
$activePage = 'payments'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Withdrawals & Payments - Syntrix</title>
  <!-- Bootstrap 5 & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body { background-color: #030b1e; color: #ffffff; font-family: sans-serif; }
    .card-dark { background-color: #081229; border: 1px solid #1e293b; border-radius: 12px; }
    
    .form-control-dark {
      background-color: #0f172a;
      border: 1px solid #334155;
      color: #ffffff;
    }
    .form-control-dark:focus {
      background-color: #0f172a;
      border-color: #5ce65c;
      color: #ffffff;
      box-shadow: 0 0 0 0.25rem rgba(92, 230, 92, 0.15);
    }
    
    /* High-contrast dark table adjustments */
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

    <!-- Main Content Workplane -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <!-- Top Title Bar Headers -->
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
        <div>
          <h1 class="h3 fw-bold mb-0 text-white">Payout Gateways</h1>
          <p class="text-muted small mb-0">Disburse available e-wallet funds, request new payouts, and audit settlement histories.</p>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <!-- Left Side: Interactive Request Cashout Form -->
        <div class="col-lg-5">
          <div class="card-dark p-4 shadow-lg h-100">
            <h5 class="fw-bold mb-3 text-white-50"><i class="bi bi-wallet2 me-2 text-success"></i>Request Withdrawal</h5>
            <div class="p-3 rounded mb-4" style="background-color: #0f172a; border-left: 4px solid #10b981;">
              <small class="text-muted d-block uppercase font-monospace">Available for Cashout</small>
              <h3 class="fw-bold text-success mb-0">$3,450.00</h3>
            </div>

            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Payout request sent to administrative queue!');">
              <div class="mb-3">
                <label class="form-label small text-muted fw-bold">Withdrawal Amount ($)</label>
                <input type="number" class="form-control form-control-dark py-2" placeholder="0.00" min="50" max="3450" required>
                <div class="form-text text-muted" style="font-size: 0.65rem;">Minimum payout entry node threshold: $50.00</div>
              </div>

              <div class="mb-4">
                <label class="form-label small text-muted fw-bold">Settlement Method</label>
                <select class="form-select form-control-dark py-2">
                  <option value="bank">Direct Bank Transfer</option>
                  <option value="crypto">USDT (Crypto Network TRC-20)</option>
                  <option value="e-wallet">Local Digital E-Wallet</option>
                </select>
              </div>

              <button type="submit" class="btn btn-brand-green w-100 py-2 text-dark fw-bold uppercase">
                Initialize Settlement
              </button>
            </form>
          </div>
        </div>

        <!-- Right Side: Active Withdrawal Ledger Table -->
        <div class="col-lg-7">
          <div class="card-dark p-4 shadow-lg h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-clock-history me-2 text-success"></i>Recent Payout Log</h5>
              <span class="badge bg-dark border border-secondary text-light px-2 py-1 small">Historical Records</span>
            </div>

            <div class="table-responsive">
              <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
                <thead>
                  <tr>
                    <th scope="col" class="py-3 px-3">Reference ID</th>
                    <th scope="col" class="py-3">Method</th>
                    <th scope="col" class="py-3">Net Outflow</th>
                    <th scope="col" class="py-3">Date</th>
                    <th scope="col" class="py-3 text-end px-3">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Processed Record Item -->
                  <tr>
                    <td class="px-3 font-monospace text-muted">PAY-55102</td>
                    <td><span class="text-light">Bank Transfer</span></td>
                    <td class="fw-bold">$1,725.00</td>
                    <td class="text-muted small">2026-06-15</td>
                    <td class="text-end px-3">
                      <span class="badge bg-success-subtle text-success border border-success px-2 py-1" style="font-size: 0.65rem;">COMPLETED</span>
                    </td>
                  </tr>
                  <!-- Pending Mock Record Item -->
                  <tr>
                    <td class="px-3 font-monospace text-muted">PAY-55940</td>
                    <td><span class="text-light">USDT Wallet</span></td>
                    <td class="fw-bold">$500.00</td>
                    <td class="text-muted small">2026-06-29</td>
                    <td class="text-end px-3">
                      <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1" style="font-size: 0.65rem;">PENDING</span>
                    </td>
                  </tr>
                </tbody>
              </table>
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