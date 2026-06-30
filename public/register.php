<?php
// 1. Loader hook to handle backend controller namespaces
spl_autoload_register(function ($class) {
    // Handle core src classes
    if (strpos($class, 'Src\\') === 0) {
        $prefix = 'Src\\';
        $base_dir = __DIR__ . '/../src/';
        $len = strlen($prefix);
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
    
    // Handle includes folder layout classes
    $file = __DIR__ . '/../includes/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require $file;
});

use Src\Controllers\RegistrationController;

$errorMessage = '';
$successMessage = '';

// 2. Form submission trigger
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new RegistrationController();
    
    // FIX: Map 'full_name' input properly to prevent registration mapping failures
    $registrationData = [
        'full_name'  => trim($_POST['full_name']),
        'username'   => trim($_POST['username']),
        'email'      => trim($_POST['email']),
        'password'   => $_POST['password'],
        'sponsor_id' => intval($_POST['sponsor_id']),
        'placement'  => $_POST['placement'], // 'Left' or 'Right'
        'plan_price' => 199.00               // Defaulting to the most popular Growth plan tier
    ];
    
    $result = $controller->handleRegistration($registrationData);
    
    if ($result['success']) {
        // Safe redirect to the login portal or onboarding step on success
        header("Location: login.php?registered=success");
        exit;
    } else {
        $errorMessage = $result['error'];
    }
}

$page = new Layout("Create Your Account - Syntrix", "register");
$page->renderHeader();
?>

<section class="hero-section py-5 d-flex align-items-center">
  <div class="container">
    <h1 class="display-6 fw-bold text-center mb-4">Join Our Intelligent Network</h1>
    
    <div class="row justify-content-center">
      <div class="col-md-6" style="max-width: 550px;">
        
        <?php if (!empty($errorMessage)): ?>
          <div class="alert alert-danger border-0 text-center mb-3 small" style="border-radius: 8px;">
            <strong>Registration Blocked:</strong> <?php echo htmlspecialchars($errorMessage); ?>
          </div>
        <?php endif; ?>

        <div class="form-container-white shadow-lg p-4" style="font-size: 0.9rem;">
          <form action="register.php" method="POST">
            
            <p class="text-muted small mb-1">Step 1: Sponsor Information</p>
            <h6 class="fw-bold text-dark mb-3">Sponsor Details</h6>
            <div class="row g-2 mb-4">
              <div class="col-6">
                <label class="form-label small text-secondary mb-1">Sponsor ID</label>
                <input type="text" name="sponsor_id" class="form-control bg-light text-center border-0 py-2 fw-semibold" placeholder="12345" required>
              </div>
              <div class="col-6">
                <label class="form-label small text-secondary mb-1">Sponsor Name</label>
                <input type="text" class="form-control bg-light text-center border-0 py-2 text-muted" placeholder="Optional Name" readonly>
              </div>
            </div>

            <p class="text-muted small mb-1">Step 2: Account Details</p>
            <h6 class="fw-bold text-dark mb-2">Create Your Profile</h6>
            <div class="row g-2 mb-2">
              <div class="col-12">
                <input type="text" name="full_name" class="form-control bg-light border-0 py-2" placeholder="Full Name" required style="border-radius: 6px;">
              </div>
            </div>
            <div class="row g-2 mb-2">
              <div class="col-12">
                <input type="email" name="email" class="form-control bg-light border-0 py-2" placeholder="Email Address" required style="border-radius: 6px;">
              </div>
            </div>
            <div class="mb-3">
              <input type="text" name="username" class="form-control bg-light border-0 py-2" placeholder="Username" required style="border-radius: 6px;">
            </div>
            <div class="row g-2 mb-4">
              <div class="col-6">
                <input type="password" name="password" class="form-control bg-light border-0 py-2" placeholder="Password" required style="border-radius: 6px;">
              </div>
              <div class="col-6">
                <input type="password" class="form-control bg-light border-0 py-2" placeholder="Confirm Password" required style="border-radius: 6px;">
              </div>
            </div>

            <p class="text-muted small mb-1">Step 3: Business Information</p>
            <h6 class="fw-bold text-dark mb-2">Network Placement</h6>
            <div class="mb-4 px-2">
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="placement" id="leftLeg" value="Left" checked>
                <label class="form-check-label text-secondary" for="leftLeg">
                  Left Leg Position
                </label>
              </div>
              <div class="form-check mb-3">
                <input class="form-check-input" type="radio" name="placement" id="rightLeg" value="Right">
                <label class="form-check-label text-secondary" for="rightLeg">
                  Right Leg Position
                </label>
              </div>
              
              <div class="form-check pt-2" style="border-top: 1px solid #f1f5f9;">
                <input class="form-check-input" type="checkbox" id="terms" checked required>
                <label class="form-check-label small text-muted" for="terms">
                  I agree to the Terms of Service and Privacy Policy
                </label>
              </div>
            </div>

            <button type="submit" class="btn btn-brand-green w-100 py-2.5 rounded-3 shadow fw-bold text-dark">
              Create My Account
            </button>
            
            <p class="text-center small text-secondary mt-3 mb-0">
              Already have an account? <a href="login.php" class="text-primary text-decoration-none fw-bold ms-1">Log In Now</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php 
$page->renderFooter(); 
?>