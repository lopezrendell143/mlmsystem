<?php
session_start();

// Guard: Force administrative security access validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

// Active page state for highlighting "User Management" in your admin sidebar
$activePage = 'user_management';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management - Syntrix Admin</title>
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
      border-color: #dc3545;
      color: #ffffff;
      box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
    }
    
    /* High-contrast administrative table layouts */
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
          <h1 class="h3 fw-bold text-white mb-0">Global User Registry</h1>
          <p class="text-muted small mb-0">Manage system permissions, alter authorization ranks, and perform administrative user overrides.</p>
        </div>
        <span class="badge bg-danger-subtle text-danger border border-danger fw-bold px-3 py-2">Root Override Matrix Active</span>
      </div>

      <div class="card-dark p-3 mb-4 shadow-sm">
        <div class="row g-3 align-items-center">
          <div class="col-md-5">
            <div class="input-group">
              <span class="input-group-text bg-dark border-secondary text-muted"><i class="bi bi-search"></i></span>
              <input type="text" class="form-control form-control-dark" placeholder="Search by name, email, or user token...">
            </div>
          </div>
          <div class="col-md-4">
            <select class="form-select form-control-dark">
              <option value="">All Security Roles</option>
              <option value="Admin">Administrators</option>
              <option value="Staff">Operations Staff</option>
              <option value="Affiliate">Network Affiliates</option>
            </select>
          </div>
          <div class="col-md-3">
            <button class="btn btn-danger w-100 fw-bold" onclick="alert('Filtering user accounts node array...');">
              Query Accounts
            </button>
          </div>
        </div>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">System Code</th>
                <th scope="col" class="py-3">Account Owner</th>
                <th scope="col" class="py-3">Assigned Role</th>
                <th scope="col" class="py-3">Network Tier</th>
                <th scope="col" class="py-3 px-3 text-end">Administrative Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-3 font-monospace text-info fw-bold">STX-1024</td>
                <td>
                  <div class="fw-bold text-white">Rendell Admin</div>
                  <small class="text-muted" style="font-size: 0.75rem;">rendell.admin@syntrix.local</small>
                </td>
                <td><span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">Admin</span></td>
                <td><span class="text-muted font-monospace">Root Level</span></td>
                <td class="px-3 text-end">
                  <button class="btn btn-sm btn-outline-light text-white font-monospace py-0.5" style="font-size: 0.75rem;" onclick="alert('Opening account alteration dashboard for user STX-1024...');">EDIT</button>
                </td>
              </tr>
              <tr>
                <td class="px-3 font-monospace text-info fw-bold">STX-2481</td>
                <td>
                  <div class="fw-bold text-white">Alex Mercer</div>
                  <small class="text-muted" style="font-size: 0.75rem;">alex.mercer@gmail.com</small>
                </td>
                <td><span class="badge bg-info-subtle text-info border border-info px-2 py-1">Affiliate</span></td>
                <td><span class="text-warning fw-semibold">Bronze Leader</span></td>
                <td class="px-3 text-end">
                  <button class="btn btn-sm btn-outline-light text-white font-monospace py-0.5" style="font-size: 0.75rem;" onclick="alert('Opening account alteration dashboard for user STX-2481...');">EDIT</button>
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