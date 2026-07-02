<?php
session_start();

// Guard: Force back-office Staff role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

// Initialize container variables for live metrics and data pools
$pendingList = [];
$awaitingCount = 0;

try {
    // Fetch count of all profiles currently awaiting processing
    $countStmt = $pdo->query("SELECT COUNT(*) FROM kyc_reviews WHERE status = 'Pending'");
    $awaitingCount = $countStmt->fetchColumn();

    // Ingest all pending validation records matching pipeline specifications
    $sql = "SELECT id, user_id, user_fullname, user_email, credential_type, submitted_file, created_at 
            FROM kyc_reviews 
            WHERE status = 'Pending' 
            ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pendingList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Graceful fallback to prevent crashes if migrations/tables are still initializing
    $pendingList = [];
    $awaitingCount = 0;
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
      background-color: #0f172a !important;
      border: 1px solid #334155 !important;
      color: #ffffff !important;
    }
    .form-control-dark::placeholder {
      color: #64748b !important;
    }
    .form-control-dark:focus {
      background-color: #0f172a !important;
      border-color: #0ea5e9 !important;
      color: #ffffff !important;
      box-shadow: 0 0 0 0.25rem rgba(14, 165, 233, 0.15) !important;
    }
    
    /* High-contrast dark data tables */
    .table-custom { color: #ffffff; border-color: #1e293b; vertical-align: middle; }
    .table-custom thead { background-color: #0f172a; color: #94a3b8; }
    
    /* Force every row table cell to render text clearly against the dark background grid */
    .table-custom tbody tr td { 
      background-color: #081229 !important; 
      color: #ffffff !important; 
      border-bottom: 1px solid #1e293b !important;
    }
    .table-custom tbody tr { border-bottom: 1px solid #1e293b; transition: background 0.15s; }
    .table-custom tbody tr:hover td { background-color: rgba(30, 41, 59, 0.5) !important; }
    
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
      
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary" style="border-color: #1e293b !important;">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">Identity & KYC Verification Desk</h1>
          <p class="text-muted small mb-0">Review submitted government credentials, audit identity validation parameters, and approve network privileges.</p>
        </div>
        <span class="badge bg-warning text-dark fw-bold px-3 py-2"><?php echo $awaitingCount; ?> Profiles Awaiting Review</span>
      </div>

      <div class="card-dark p-4 shadow-lg">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
          <h5 class="fw-bold mb-0 text-white-50"><i class="bi bi-shield-check me-2 text-info"></i>Pending Document Registry</h5>
          <input type="text" class="form-control form-control-dark py-1 px-3" placeholder="Filter by User ID or Name..." style="width: 260px; font-size: 0.85rem;">
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
  <?php if (count($pendingList) > 0): ?>
    <?php foreach ($pendingList as $row): ?>
      <tr>
        <td class="px-3 font-monospace text-info fw-bold">STX-<?php echo htmlspecialchars(str_pad($row['user_id'], 5, '0', STR_PAD_LEFT)); ?></td>
        <td>
          <div class="fw-bold text-white"><?php echo htmlspecialchars($row['user_fullname']); ?></div>
          <span class="text-muted small" style="font-size: 0.7rem;">Email: <?php echo htmlspecialchars($row['user_email']); ?></span>
        </td>
        <td><span class="text-light"><?php echo htmlspecialchars($row['credential_type']); ?></span></td>
        <td>
          <div class="document-preview-node" onclick="alert('Displaying secure raw credential node image context for STX-<?php echo htmlspecialchars($row['user_id']); ?>...');">
            <i class="bi bi-file-earmark-image text-info"></i> <?php echo htmlspecialchars($row['submitted_file']); ?>
          </div>
        </td>
        <td class="text-muted small"><?php echo htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))); ?></td>
        <td class="text-end px-3">
          <form action="verify_action.php" method="POST" class="d-inline">
            <input type="hidden" name="kyc_id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="status_action" value="Approved">
            <button type="submit" class="btn btn-sm btn-success font-monospace fw-bold me-1 py-1" style="font-size: 0.75rem;">APPROVE</button>
          </form>
          
          <form action="verify_action.php" method="POST" class="d-inline">
            <input type="hidden" name="kyc_id" value="<?php echo $row['id']; ?>">
            <input type="hidden" name="status_action" value="Rejected">
            <button type="submit" class="btn btn-sm btn-outline-danger font-monospace py-1" style="font-size: 0.75rem;">REJECT</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="6" class="text-center py-4 font-monospace text-muted">
        <i class="bi bi-folder2-open me-2"></i>No pending verification submissions found inside the queue registry.
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>