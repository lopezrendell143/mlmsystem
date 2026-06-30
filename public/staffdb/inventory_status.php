<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
    exit;
}

// Active page state for highlighting "Inventory Status" in your staff sidebar
$activePage = 'inventory_status';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Status Desk - Syntrix</title>
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
          <h1 class="h3 fw-bold text-white mb-0">Warehouse & Stock Logistics</h1>
          <p class="text-muted small mb-0">Track product item distributions, modify package inventory levels, and check stock statuses.</p>
        </div>
        <span class="badge bg-info text-dark fw-bold px-3 py-2">124 Items Ready for Ship</span>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-box-seam me-2 text-info"></i>Product Stock Registry</h5>
          <button class="btn btn-sm btn-info text-dark fw-bold px-3" onclick="alert('Opening stock adjustment panel...');">
            <i class="bi bi-plus-lg me-1"></i> Add New Product
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">SKU Code</th>
                <th scope="col" class="py-3">Product Name</th>
                <th scope="col" class="py-3">Category Group</th>
                <th scope="col" class="py-3">Unit Price</th>
                <th scope="col" class="py-3">Stock Remainder</th>
                <th scope="col" class="py-3 text-end px-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="px-3 font-monospace text-info fw-bold">PROD-EL-992</td>
                <td>
                  <div class="fw-bold text-white">Syntrix Quantum Tablet</div>
                  <span class="text-muted small" style="font-size: 0.7rem;">Package: Premium Affiliate Bundle</span>
                </td>
                <td><span class="text-light">Electronics</span></td>
                <td class="fw-bold text-white font-monospace">$299.00</td>
                <td>
                  <div class="fw-bold text-white">42 Units</div>
                  <div class="progress mt-1" style="height: 4px; background-color: #1e293b;">
                    <div class="progress-bar bg-info" style="width: 42%;"></div>
                  </div>
                </td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-outline-info me-1 py-1" style="font-size: 0.75rem;" onclick="alert('Modifying point limits for item PROD-EL-992...');"><i class="bi bi-pencil-fill"></i></button>
                </td>
              </tr>
              
              <tr>
                <td class="px-3 font-monospace text-info fw-bold">PROD-PH-412</td>
                <td>
                  <div class="fw-bold text-white">Syntrix Alpha Smart Phone</div>
                  <span class="text-muted small" style="font-size: 0.7rem;">Package: Basic Entry Node</span>
                </td>
                <td><span class="text-light">Electronics</span></td>
                <td class="fw-bold text-white font-monospace">$199.00</td>
                <td>
                  <div class="fw-bold text-danger">5 Units <i class="bi bi-exclamation-triangle-fill ms-1"></i></div>
                  <div class="progress mt-1" style="height: 4px; background-color: #1e293b;">
                    <div class="progress-bar bg-danger" style="width: 8%;"></div>
                  </div>
                </td>
                <td class="text-end px-3">
                  <button class="btn btn-sm btn-outline-info me-1 py-1" style="font-size: 0.75rem;" onclick="alert('Modifying point limits for item PROD-PH-412...');"><i class="bi bi-pencil-fill"></i></button>
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