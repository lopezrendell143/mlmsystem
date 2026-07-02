<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

// --- INBOUND GOODS PROCESSOR BLOCK ---
$insertSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
    $packageCode = trim($_POST['package_code']);
    $recipientName = trim($_POST['recipient_name']);
    $itemDetails = trim($_POST['item_details']);
    $status = trim($_POST['status']);

    if (!empty($packageCode) && !empty($recipientName)) {
        try {
            // Executing layout alignment mapping against the freshly modified database columns
            $insertSql = "INSERT INTO warehouse_fulfillment (package_code, recipient_name, item_details, status, created_at) 
                          VALUES (:package_code, :recipient_name, :item_details, :status, NOW())";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->execute([
                'package_code' => $packageCode,
                'recipient_name' => $recipientName,
                'item_details' => $itemDetails,
                'status' => $status
            ]);
            $insertSuccess = true;
        } catch (PDOException $e) {
            // Error handling layer
        }
    }
}

// 2. LIVE DATA FETCH QUEUE
$inventoryItems = [];
try {
    $sql = "SELECT id, package_code, recipient_name, item_details, status FROM warehouse_fulfillment ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $inventoryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $inventoryItems = [];
}

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
      background-color: #0f172a !important;
      border: 1px solid #334155 !important;
      color: #ffffff !important;
    }
    .form-control-dark::placeholder { color: #64748b !important; }
    .form-control-dark:focus {
      background-color: #0f172a !important;
      border-color: #0ea5e9 !important;
      color: #ffffff !important;
      box-shadow: 0 0 0 0.25rem rgba(14, 165, 233, 0.15) !important;
    }
    
    .table-custom { color: #ffffff !important; border-color: #1e293b; vertical-align: middle; }
    .table-custom thead th { background-color: #0f172a !important; color: #94a3b8 !important; border-bottom: 2px solid #1e293b; }
    
    .table-custom tbody tr td { 
      background-color: #081229 !important; 
      color: #ffffff !important; 
      border-bottom: 1px solid #1e293b !important;
    }
    .table-custom tbody tr { border-bottom: 1px solid #1e293b; transition: background 0.15s; }
    .table-custom tbody tr:hover td { background-color: rgba(30, 41, 59, 0.5) !important; }

    .modal-content-dark { background-color: #081229; border: 1px solid #1e293b; color: #ffffff; }
    .modal-header-dark { border-bottom: 1px solid #1e293b; }
    .modal-footer-dark { border-top: 1px solid #1e293b; }

    .toast-container-custom { position: fixed; top: 24px; right: 24px; z-index: 1090; }
    .toast-dark { background-color: #081229 !important; border: 1px solid #0ea5e9 !important; color: #ffffff !important; border-radius: 8px; }
    .toast-dark .toast-header { background-color: #0f172a !important; color: #38bdf8 !important; border-bottom: 1px solid #1e293b; }
  </style>
</head>
<body>

<!-- SYSTEM PROCESS TOAST COMPONENT -->
<div class="toast-container toast-container-custom">
  <div id="saveNotificationToast" class="toast toast-dark shadow-lg hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
    <div class="toast-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <i class="bi bi-cpu text-info"></i>
        <strong class="font-monospace small fw-bold text-uppercase">System Pipeline Node</strong>
      </div>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body small font-monospace">
      <i class="bi bi-database-check text-success me-2"></i>Record committed to warehouse database stream successfully.
    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary" style="border-color: #1e293b !important;">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">Warehouse & Stock Logistics</h1>
          <p class="text-muted small mb-0">Track product item distributions, modify package inventory levels, and check stock statuses.</p>
        </div>
        <span class="badge bg-info text-dark fw-bold px-3 py-2"><?php echo count($inventoryItems); ?> Items Ready for Ship</span>
      </div>

      <?php if ($insertSuccess): ?>
        <div class="alert alert-success bg-success-subtle text-success border border-success d-flex align-items-center mb-4" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>
          <div>Logistics node product entry appended successfully into warehouse database table map!</div>
        </div>
      <?php endif; ?>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-box-seam me-2 text-info"></i>Product Stock Registry</h5>
          <button class="btn btn-sm btn-info text-dark fw-bold font-monospace" data-bs-toggle="modal" data-bs-target="#addProductModal" style="font-size: 0.85rem;"><i class="bi bi-plus-lg me-1"></i>Add New Product</button>
        </div>

        <div class="table-responsive">
          <table class="table table-custom table-hover mb-0" style="font-size: 0.9rem;">
            <thead>
              <tr>
                <th scope="col" class="py-3 px-3">SKU Code</th>
                <th scope="col" class="py-3">Product Name / Recipient</th>
                <th scope="col" class="py-3">Category Group / Details</th>
                <th scope="col" class="py-3">Stock Remainder / Status</th>
                <th scope="col" class="py-3 text-end px-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($inventoryItems) > 0): ?>
                <?php foreach ($inventoryItems as $item): ?>
                  <tr>
                    <td class="px-3 font-monospace text-info fw-bold"><?php echo htmlspecialchars($item['package_code'] ?? 'N/A'); ?></td>
                    <td>
                      <div class="fw-bold text-white"><?php echo htmlspecialchars($item['recipient_name'] ?? 'Unassigned'); ?></div>
                    </td>
                    <td><span class="text-muted small"><?php echo htmlspecialchars($item['item_details'] ?? '-'); ?></span></td>
                    <td>
                      <?php if (strtolower($item['status']) === 'ready' || strtolower($item['status']) === 'active'): ?>
                        <span class="badge bg-success-subtle text-success border border-success px-2 py-0.5">READY</span>
                      <?php else: ?>
                        <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-0.5"><?php echo strtoupper(htmlspecialchars($item['status'])); ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="text-end px-3">
                      <button class="btn btn-sm btn-outline-light font-monospace py-1" style="font-size: 0.75rem;" onclick="alert('Modifying structural stock node mapping context...');"><i class="bi bi-pencil"></i></button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center py-4 font-monospace text-muted">
                    <i class="bi bi-patch-minus me-2"></i>No distribution items cataloged in warehouse database pipelines.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

<!-- ADD PRODUCT MODAL WORKSPACE -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-content-dark shadow-lg">
      <div class="modal-header modal-header-dark">
        <h5 class="modal-title fw-bold text-white" id="addProductModalLabel"><i class="bi bi-box-seam me-2 text-info"></i>Register Distribution Product</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="inventory_status.php" method="POST">
        <input type="hidden" name="action" value="add_product">
        <div class="modal-body">
          
          <div class="mb-3">
            <label class="form-label small text-muted font-monospace fw-bold">SKU / PACKAGE CODE</label>
            <input type="text" name="package_code" class="form-control form-control-dark font-monospace" placeholder="e.g. PKG-1092" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label small text-muted font-monospace fw-bold">PRODUCT NAME / RECIPIENT</label>
            <input type="text" name="recipient_name" class="form-control form-control-dark" placeholder="e.g. Premium Upgrade Package" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label small text-muted font-monospace fw-bold">CATEGORY GROUP / ITEM DETAILS</label>
            <textarea name="item_details" class="form-control form-control-dark small" rows="3" placeholder="Specify contents context description..."></textarea>
          </div>
          
          <div class="mb-3">
            <label class="form-label small text-muted font-monospace fw-bold">INITIAL PIPELINE STATUS</label>
            <select name="status" class="form-select form-control-dark">
              <option value="Ready" selected>Ready (Active)</option>
              <option value="Pending">Pending Audit</option>
              <option value="Dispatched">Dispatched</option>
            </select>
          </div>

        </div>
        <div class="modal-footer modal-footer-dark">
          <button type="button" class="btn btn-sm btn-outline-secondary font-monospace" data-bs-dismiss="modal">CANCEL</button>
          <button type="submit" class="btn btn-sm btn-info text-dark fw-bold font-monospace px-3">SAVE RECORD</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    <?php if ($insertSuccess): ?>
      const toastElement = document.getElementById('saveNotificationToast');
      if (toastElement) {
        const liveToast = new bootstrap.Toast(toastElement);
        liveToast.show();
      }
    <?php endif; ?>
  });
</script>
</body>
</html>