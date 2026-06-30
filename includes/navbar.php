<?php
// Safety check: Ensure $activePage variable is at least initialized if loaded outside Layout context
if (!isset($activePage)) {
    $activePage = 'home';
}
?>
<!-- Global Navbar (Sticky on Scroll) -->
<nav class="navbar navbar-expand-md navbar-dark navbar-custom sticky-top px-4 py-3">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <div class="logo-box">S</div>
      <span class="fw-semibold text-white">Syntrix</span>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center gap-3">
        <li class="nav-item"><a class="nav-link <?php echo $activePage == 'home' ? 'active fw-bold' : 'text-light-50'; ?>" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $activePage == 'about' ? 'active fw-bold' : 'text-light-50'; ?>" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $activePage == 'plans' ? 'active fw-bold' : 'text-light-50'; ?>" href="plans.php">Plans</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $activePage == 'contact' ? 'active fw-bold' : 'text-light-50'; ?>" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $activePage == 'login' ? 'active fw-bold' : 'text-light-50'; ?>" href="login.php">Login</a></li>
        <li class="nav-item ms-md-2">
          <a href="register.php" class="btn btn-brand-green px-4 py-2 rounded-3 shadow">Join Now</a>
        </li>
      </ul>
    </div>
  </div>
</nav>