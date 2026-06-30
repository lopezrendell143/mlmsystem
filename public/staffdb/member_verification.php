<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
    exit;
}

// Active page state for highlighting "Member Verification" in your staff sidebar
$activePage = 'member_verification';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Member Verification Desk - Syntrix</title>
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
    
    /* High-contrast dark data tables */
    .table-custom { color: #ffffff; border-color: #1e293b; vertical-align: middle; }
    .table-custom thead { background-color: #0f172a; color: #94a3b8; }
    .table-custom tbody tr { border-bottom: 1px solid #1e293b; transition: background 0.15s; }
    .table-custom tbody tr:hover { background-color: rgba(30, 41, 59, 0.5); }
    
    /* Document preview node placeholder cards */
    .document-preview-node {
      background-color: #0f172a;
      border: 1px dashed #475569;
      border-radius: 6px;
      color: #94a3b8;
      font-size: 0.75rem;
      padding: 4px 8px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: border-color 0.15s, color 0.15s;
    }
    .document-preview-node:hover {
      border-color: #38bdf8;
      color: #ffffff;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">Identity & KYC Verification Desk</h1>
          <p class="text-muted small mb-0">Review submitted government credentials, audit identity validation parameters, and approve network privileges.</p>
        </div>
        <span class="badge bg-warning text-dark fw-bold px-3 py-2">32 Profiles Awaiting Review</span>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-shield-check me-2 text-info"></i>Pending Document Registry</h5>
          <input type="text" class="form-control form-control-dark py-1 px-3 style-placeholder" placeholder="Filter by User ID or Name..." style="width: 260px; font-size: 0.85rem;">
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">User ID</th>
                <th scope="col" class="py-3">Member Name</th>
                <th scope="col" class="py-3">Credential Type</th>
                <th scope="col" class="py-3">Submitted Files</th>
                <th scope="col" class="py-3">Submission Date</th>
                <th scope="col" class="py-3 text-end px-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-3 font-monospace text-info fw-bold">STX-00241</td>
                <td>
                  <div class="fw-bold text-white">Alex Mercer</div>
                  <span class="text-muted small" style="font-size: 0.7rem;">Email: alex.mercer@gmail.com</span>
                </td>
                <td><span class="text-light">Passport / National ID</span></td>
                <td>
                  <div class="document-preview-node" onclick="alert('Displaying secure raw credential node image context for STX-00241...');">
                    <i class="bi bi-file-earmark-image text-info"></i> passport_front.jpg
                  </div>
                </td>
                <td class="text-muted small">2026-06-29</td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-success font-monospace fw-bold me-1 py-1" style="font-size: 0.75rem;" onclick="alert('User identity approved successfully! Notification sent to network router.');">APPROVE</button>
                  <button class="btn btn-sm btn-outline-danger font-monospace py-1" style="font-size: 0.75rem;" onclick="alert('KYC verification rejected. Opening reason terminal...');">REJECT</button>
                </td>
              </tr>
              
              <tr>
                <td class="px-3 font-monospace text-info fw-bold">STX-00312</td>
                <td>
                  <div class="fw-bold text-white">Elena Kyle</div>
                  <span class="text-muted small" style="font-size: 0.7rem;">Email: elena.kyle@gmail.com</span>
                </td>
                <td><span class="text-light">Driver's License</span></td>
                <td>
                  <div class="document-preview-node" onclick="alert('Displaying secure raw credential node image context for STX-00312...');">
                    <i class="bi bi-file-earmark-image text-info"></i> license_scan.pdf
                  </div>
                </td>
                <td class="text-muted small">2026-06-30</td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-success font-monospace fw-bold me-1 py-1" style="font-size: 0.75rem;" onclick="alert('User identity approved successfully! Notification sent to network router.');">APPROVE</button>
                  <button class="btn btn-sm btn-outline-danger font-monospace py-1" style="font-size: 0.75rem;" onclick="alert('KYC verification rejected. Opening reason terminal...');">REJECT</button>
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