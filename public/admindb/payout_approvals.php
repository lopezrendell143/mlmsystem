<?php
session_start();

// Guard: Force administrative security access validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

// Active page state for highlighting "Payout Approvals" in your admin sidebar
$activePage = 'payout_approvals';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payout Approvals - Syntrix Admin</title>
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
      
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">E-Wallet Payout Approvals</h1>
          <p class="text-muted small mb-0">Audit withdrawal ledger requests, check blockchain endpoint destination hashes, and authorize payouts.</p>
        </div>
        <span class="badge bg-danger text-white fw-bold px-3 py-2">$18,400 Pending Disbursal</span>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-wallet2 me-2 text-danger"></i>Pending Withdrawal Queue</h5>
          <span class="text-muted small">Total: 2 Queue Items</span>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">Member Details</th>
                <th scope="col" class="py-3">Withdrawal Pathway</th>
                <th scope="col" class="py-3">Destination Address Details</th>
                <th scope="col" class="py-3">Gross Amount</th>
                <th scope="col" class="py-3 text-end px-3">Authorization Rules</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-3">
                  <div class="fw-bold text-white">Sarah Connor</div>
                  <small class="text-muted font-monospace" style="font-size: 0.75rem;">User Reference ID: #1088</small>
                </td>
                <td><span class="badge bg-dark text-info border border-info">USDT (TRC-20)</span></td>
                <td><span class="font-monospace text-white-50 text-wrap" style="font-size: 0.8rem;">TX9rK...mN2pZ7bWv8QeA</span></td>
                <td><span class="text-danger fw-bold font-monospace">$1,200.00</span></td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-danger fw-bold me-1 py-1 px-3" style="font-size: 0.75rem;" onclick="alert('Transaction node released. Injecting cryptographic hash...');">RELEASE</button>
                  <button class="btn btn-sm btn-outline-secondary py-1" style="font-size: 0.75rem;" onclick="alert('Disbursal suspended for secondary validation checks...');">REJECT</button>
                </td>
              </tr>
              
              <tr>
                <td class="px-3">
                  <div class="fw-bold text-white">John Doe</div>
                  <small class="text-muted font-monospace" style="font-size: 0.75rem;">User Reference ID: #1105</small>
                </td>
                <td><span class="badge bg-dark text-info border border-info">Bank Wire Transfer</span></td>
                <td><span class="text-white-50 small">Chase Bank — Acc ending in *4829</span></td>
                <td><span class="text-danger fw-bold font-monospace">$4,500.00</span></td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-danger fw-bold me-1 py-1 px-3" style="font-size: 0.75rem;" onclick="alert('Transaction node released. Injecting cryptographic hash...');">RELEASE</button>
                  <button class="btn btn-sm btn-outline-secondary py-1" style="font-size: 0.75rem;" onclick="alert('Disbursal suspended for secondary validation checks...');">REJECT</button>
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