<?php
session_start();

// Include the newly created connection script
require_once __DIR__ . '/../config/database.php';

// Initialize feedback message variable
$error_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($email) && !empty($password)) {
        try {
            // Secure SQL query using standard database columns (full_name AS name)
            $stmt = $pdo->prepare("SELECT id, email, password, role, full_name AS name FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            // Validate credentials against live database records (Plain Text Comparison)
            if ($user && $password === $user['password']) {
                
                // Core session payload mapping required by includes/sidebar.php
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['username']  = $user['name'];
                
                // Dynamically route to the designated subfolder hub depending on the user role
                switch ($user['role']) {
                    case 'Admin':
                        header("Location: admindb/admin_dashboard.php");
                        break;
                    case 'Staff':
                        header("Location: staffdb/staff_dashboard.php");
                        break;
                    case 'Affiliate':
                    default:
                        header("Location: affiliatedb/dashboard.php");
                        break;
                }
                exit;
            } else {
                $error_message = 'Invalid email address or security access password.';
            }
        } catch (PDOException $e) {
            // Restored the user-friendly clean interface error message
            $error_message = 'Authentication service encountered an internal database error.';
        }
    } else {
        $error_message = 'Please fill out both credential parameters.';
    }
}

// Render the landing page design wrapper layout
require_once __DIR__ . '/../includes/Layout.php';
$layout = new Layout("Sign In - Syntrix", "login");
$layout->renderHeader();
?>

<style>
  body { 
    background: linear-gradient(rgba(3, 11, 30, 0.8), rgba(3, 11, 30, 0.9)), url('assets/images/ggs.avif') no-repeat center center fixed !important; 
    background-size: cover !important;
  }
</style>

<div class="container my-5 py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      
      <div class="form-container-white shadow-lg">
        <h3 class="fw-bold mb-1 text-dark text-center">Welcome</h3>
        <p class="text-muted small text-center mb-4">Sign in to your Syntrix portal node</p>

        <?php if (isset($_GET['registered']) && $_GET['registered'] === 'true'): ?>
            <div class="alert alert-success border-0 text-center mb-3 small py-2 fw-semibold" style="border-radius: 8px; background-color: #d1fae5; color: #065f46;">
                <i class="bi bi-check-circle-fill me-2"></i>Account configured successfully! You can now log in.
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger small py-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
          <div class="mb-3">
            <label for="email" class="form-label text-dark small fw-semibold">Email Address</label>
            <input type="email" class="form-control py-2 text-dark" id="email" name="email" placeholder="name@gmail.com" required>
          </div>
          
          <div class="mb-4">
            <label for="password" class="form-label text-dark small fw-semibold">Password</label>
            <input type="password" class="form-control py-2 text-dark" id="password" name="password" placeholder="••••••••" required>
          </div>

          <button type="submit" class="btn btn-brand-green w-100 py-2.5 rounded-3 shadow text-dark fw-bold uppercase tracking-wider">
            Authorize Workspace
          </button>
        </form>
        
        <div class="text-center mt-4">
            <span class="text-muted small">New to the platform?</span> 
            <a href="register.php" class="small fw-bold text-decoration-none" style="color: #10b981;">Create an account</a>
        </div>
      </div>

    </div>
  </div>
</div>

<?php 
$layout->renderFooter(); 
?>