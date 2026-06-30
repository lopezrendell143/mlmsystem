<?php
session_start();

// Guard: Force affiliate access role validation
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Affiliate') {
    header("Location: ../login.php");
    exit;
}

// Set active page flag so the sidebar highlights "My Profile" correctly
$activePage = 'profile'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile - Syntrix</title>
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
    .profile-avatar-large {
      width: 90px; height: 90px;
      border-radius: 50%;
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.2), rgba(16, 185, 129, 0.2));
      border: 2px solid #10b981;
      display: flex; align-items: center; justify-content: center;
      font-size: 2.2rem; font-weight: bold; color: #5ce65c;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    
    <!-- Dynamic Sidebar Component Injection (2 levels up) -->
    <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

    <!-- Main Content Workplane -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
      
      <!-- Top Title Bar Headers -->
      <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
        <div>
          <h1 class="h3 fw-bold mb-0 text-white">Account Management</h1>
          <p class="text-muted small mb-0">View your rank metrics, adjust account keys, and update security credentials.</p>
        </div>
      </div>

      <div class="row g-4">
        <!-- Left Side: Profile Badge Details -->
        <div class="col-lg-4">
          <div class="card-dark p-4 text-center shadow-lg h-100 d-flex flex-column align-items-center justify-content-center">
            <div class="profile-avatar-large mb-3">
              <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
            </div>
            <h4 class="fw-bold mb-1 text-white"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User Account'); ?></h4>
            <span class="badge bg-warning text-dark px-3 py-1.5 fw-bold mb-3" style="font-size: 0.75rem;">BRONZE LEADER</span>
            
            <hr class="w-100 border-secondary my-3">
            
            <div class="w-100 text-start small text-muted space-y-2">
              <div class="d-flex justify-content-between mb-2">
                <span>Account ID:</span>
                <span class="text-light fw-mono">STX-00134</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span>Placement Leg:</span>
                <span class="text-info">Balanced Matrix</span>
              </div>
              <div class="d-flex justify-content-between">
                <span>Joined Date:</span>
                <span class="text-light">June 2026</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Side: Form Credentials Editable Workspace -->
        <div class="col-lg-8">
          <div class="card-dark p-4 shadow-lg">
            <h5 class="fw-bold mb-4 text-white-50"><i class="bi bi-person-gear me-2 text-success"></i>Profile Settings</h5>
            
            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Profile update workflow ready for integration!');">
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small text-muted fw-bold">Username</label>
                  <input type="text" class="form-control form-control-dark" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted fw-bold">Email Address</label>
                  <input type="email" class="form-control form-control-dark" value="affiliate@gmail.com" readonly disabled>
                  <div class="form-text text-muted" style="font-size: 0.7rem;">Contact operations to modify registered authentication emails.</div>
                </div>
              </div>

              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label small text-muted fw-bold">New Security Password</label>
                  <input type="password" class="form-control form-control-dark" placeholder="••••••••">
                </div>
                <div class="col-md-6">
                  <label class="form-label small text-muted fw-bold">Confirm New Password</label>
                  <input type="password" class="form-control form-control-dark" placeholder="••••••••">
                </div>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary text-white px-4">Reset</button>
                <button type="submit" class="btn btn-sm btn-success px-4" style="background-color: #10b981; border-color: #10b981;">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>