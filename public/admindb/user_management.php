<?php
session_start();

// Guard: Force administrative security access validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

// 1. DATABASE CONNECTIVITY MAPPER
require_once __DIR__ . '/../../config/database.php';

// Instantiate session status messages tracking variable
$updateSuccess = false;
if (isset($_SESSION['update_success_flash'])) {
    $updateSuccess = true;
    unset($_SESSION['update_success_flash']);
}

// --- MODAL SUBMISSION OVERRIDE PROCESSING TRACKER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_user') {
    $userId = intval($_POST['user_id']);
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $placement = trim($_POST['placement']);

    try {
        $updateSql = "UPDATE users SET full_name = :full_name, email = :email, role = :role, placement = :placement WHERE id = :id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([
            ':full_name' => $fullName,
            ':email'     => $email,
            ':role'      => $role,
            ':placement' => $placement,
            ':id'        => $userId
        ]);
        
        // Set state notification flag inside session storage wrapper
        $_SESSION['update_success_flash'] = true;
        
        // Soft refresh to load updated values cleanly
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } catch (PDOException $e) {
        // Soft fail catch
    }
}

// 2. RETRIEVE FILTER & SEARCH QUERY CONSTANTS
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$roleFilter = isset($_GET['role']) ? trim($_GET['role']) : '';

try {
    // Construct base parameters using your exact column names
    $sql = "SELECT * FROM users WHERE 1=1";
    $bindings = [];

    if ($searchKeyword !== '') {
        $sql .= " AND (full_name LIKE :search OR username LIKE :search OR email LIKE :search OR id LIKE :search)";
        $bindings[':search'] = '%' . $searchKeyword . '%';
    }

    if ($roleFilter !== '') {
        $sql .= " AND role = :role"; 
        $bindings[':role'] = $roleFilter;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($bindings);
    $registeredUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $registeredUsers = [];
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
    /* Deep space background core setup */
    body { background-color: #020714; color: #cbd5e1; font-family: sans-serif; }
    
    /* Clean custom deep-navy outer card boxes */
    .card-dark { 
      background-color: #050c1e !important; 
      border: 1px solid #0f1c3f !important; 
      border-radius: 12px; 
    }
    
    .form-control-dark {
      background-color: #020714;
      border: 1px solid #14254c;
      color: #ffffff;
    }
    .form-control-dark:focus {
      background-color: #020714;
      border-color: #dc3545;
      color: #ffffff;
      box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
    }
    
    /* CRITICAL OVERRIDES: Forces every single row cell to be dark, destroying the white background bug */
    .table-custom { 
      border-color: #0f1c3f !important; 
      vertical-align: middle; 
      background-color: #050c1e !important;
    }
    
    .table-custom thead th { 
      background-color: #020714 !important; 
      color: #64748b !important; 
      border-bottom: 2px solid #0f1c3f !important;
      font-weight: 600;
    }
    
    .table-custom tbody tr td { 
      background-color: #050c1e !important; 
      border-bottom: 1px solid #0f1c3f !important;
      transition: background 0.15s;
    }
    
    /* Interactive highlighting tracking over dark nodes */
    .table-custom tbody tr:hover td { 
      background-color: #09132d !important; 
    }
    
    /* High contrast typography forcing full text visibility over dark canvas layer */
    .text-owner-title { 
      color: #ffffff !important; 
      font-weight: 700 !important; 
      font-size: 0.95rem;
      display: block;
    }
    .text-owner-subtitle { 
      color: #8a99ad !important; 
      display: block;
    }
    .text-placement-node { 
      color: #f59e0b !important; 
      font-weight: 600; 
    }

    /* Modal styling strictly adhering to the ultra-dark design matrix */
    .modal-dark-content {
      background-color: #050c1e !important;
      border: 1px solid #0f1c3f !important;
      color: #e2e8f0 !important;
      border-radius: 14px;
    }
    .modal-dark-header {
      border-bottom: 1px solid #0f1c3f !important;
      background-color: #020714 !important;
      border-top-left-radius: 13px !important;
      border-top-right-radius: 13px !important;
    }
    .modal-dark-footer {
      border-top: 1px solid #0f1c3f !important;
      background-color: #020714 !important;
      border-bottom-left-radius: 13px !important;
      border-bottom-right-radius: 13px !important;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom" style="border-color: #0f1c3f !important;">
        <div>
          <h1 class="h3 fw-bold text-white mb-0">Global User Registry</h1>
          <p class="text-muted small mb-0">Manage system permissions, alter authorization ranks, and perform administrative user overrides.</p>
        </div>
        <span class="badge bg-danger-subtle text-danger border border-danger fw-bold px-3 py-2">Root Override Matrix Active</span>
      </div>

      <?php if ($updateSuccess): ?>
        <div class="alert alert-dismissible fade show border-0 shadow p-3 mb-4" style="background-color: #05211b; border: 1px solid #0e4438 !important; border-radius: 10px;" role="alert">
          <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill text-success fs-5 me-3"></i>
            <div>
              <strong class="text-white">Account Alteration Successful</strong>
              <div class="text-muted small mt-0.5">The network node parameters have been securely stored in the master database ledge matrix.</div>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.75rem; top: 1rem;"></button>
        </div>
      <?php endif; ?>

      <div class="card-dark p-3 mb-4 shadow-sm">
        <form action="" method="GET">
          <div class="row g-3 align-items-center">
            <div class="col-md-5">
              <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-color: #14254c !important;"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control form-control-dark" placeholder="Search by name, email, or user token..." value="<?php echo htmlspecialchars($searchKeyword); ?>">
              </div>
            </div>
            <div class="col-md-4">
              <select name="role" class="form-select form-control-dark">
                <option value="">All Security Roles</option>
                <option value="Admin" <?php echo $roleFilter === 'Admin' ? 'selected' : ''; ?>>Administrators</option>
                <option value="Staff" <?php echo $roleFilter === 'Staff' ? 'selected' : ''; ?>>Operations Staff</option>
                <option value="Affiliate" <?php echo $roleFilter === 'Affiliate' ? 'selected' : ''; ?>>Network Affiliates</option>
              </select>
            </div>
            <div class="col-md-3">
              <button type="submit" class="btn btn-danger w-100 fw-bold">
                Query Accounts
              </button>
            </div>
          </div>
        </form>
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
              <?php if (count($registeredUsers) > 0): ?>
                <?php foreach ($registeredUsers as $user): ?>
                  <tr>
                    <td class="px-3 font-monospace text-info fw-bold">STX-<?php echo htmlspecialchars($user['id']); ?></td>
                    <td>
                      <span class="text-owner-title"><?php echo htmlspecialchars($user['full_name']); ?></span>
                      <small class="text-owner-subtitle" style="font-size: 0.75rem;"><?php echo htmlspecialchars($user['email']); ?></small>
                    </td>
                    <td>
                      <?php 
                        $badgeClass = 'bg-info-subtle text-info border border-info';
                        if ($user['role'] === 'Admin') {
                            $badgeClass = 'bg-danger-subtle text-danger border border-danger';
                        } elseif ($user['role'] === 'Staff') {
                            $badgeClass = 'bg-warning-subtle text-warning border border-warning';
                        }
                      ?>
                      <span class="badge <?php echo $badgeClass; ?> px-2 py-1">
                        <?php echo htmlspecialchars($user['role']); ?>
                      </span>
                    </td>
                    <td>
                      <?php if ($user['role'] === 'Admin'): ?>
                        <span class="text-muted font-monospace">Root Level</span>
                      <?php else: ?>
                        <span class="text-placement-node"><?php echo htmlspecialchars($user['placement'] ?? 'None'); ?> Side</span>
                      <?php endif; ?>
                    </td>
                    <td class="px-3 text-end">
                      <button type="button" class="btn btn-sm btn-outline-light text-white font-monospace py-0.5 btn-edit-trigger" 
                              style="font-size: 0.75rem; border-color: #334155;"
                              data-id="<?php echo $user['id']; ?>"
                              data-name="<?php echo htmlspecialchars($user['full_name']); ?>"
                              data-email="<?php echo htmlspecialchars($user['email']); ?>"
                              data-role="<?php echo htmlspecialchars($user['role']); ?>"
                              data-placement="<?php echo htmlspecialchars($user['placement'] ?? 'None'); ?>">
                        EDIT
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center text-muted py-4" style="background-color: #050c1e !important;">No network nodes found matching current query credentials.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-dark-content">
      <form action="" method="POST">
        <input type="hidden" name="action" value="update_user">
        <input type="hidden" name="user_id" id="edit_user_id">
        
        <div class="modal-header modal-dark-header">
          <h5 class="modal-title fw-bold text-white"><i class="bi bi-shield-lock me-2 text-danger"></i>Alter Authorization Rank</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small text-muted fw-bold text-uppercase">Account Owner Name</label>
            <input type="text" name="full_name" id="edit_full_name" class="form-control form-control-dark" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label small text-muted fw-bold text-uppercase">Secure Contact Email Address</label>
            <input type="email" name="email" id="edit_email" class="form-control form-control-dark" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label small text-muted fw-bold text-uppercase">System Access Security Role</label>
            <select name="role" id="edit_role" class="form-select form-control-dark" required>
              <option value="Admin">Admin</option>
              <option value="Staff">Staff</option>
              <option value="Affiliate">Affiliate</option>
            </select>
          </div>
          
          <div class="mb-2">
            <label class="form-label small text-muted fw-bold text-uppercase">Network Placement Tier</label>
            <select name="placement" id="edit_placement" class="form-select form-control-dark">
              <option value="Left">Left Side</option>
              <option value="Right">Right Side</option>
              <option value="None">None (Root Layer)</option>
            </select>
          </div>
        </div>
        
        <div class="modal-footer modal-dark-footer">
          <button type="button" class="btn btn-outline-secondary font-monospace" style="font-size: 0.85rem;" data-bs-dismiss="modal">CANCEL</button>
          <button type="submit" class="btn btn-danger fw-bold font-monospace" style="font-size: 0.85rem;">SAVE CHANGES</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    
    document.querySelectorAll('.btn-edit-trigger').forEach(button => {
        button.addEventListener('click', function() {
            // Extract attributes matching data parameters directly from the clicked node
            document.getElementById('edit_user_id').value = this.getAttribute('data-id');
            document.getElementById('edit_full_name').value = this.getAttribute('data-name');
            document.getElementById('edit_email').value = this.getAttribute('data-email');
            document.getElementById('edit_role').value = this.getAttribute('data-role');
            document.getElementById('edit_placement').value = this.getAttribute('data-placement');
            
            // Render the dialog system cleanly
            editModal.show();
        });
    });
});
</script>
</body>
</html>