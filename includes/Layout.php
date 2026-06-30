<?php
class Layout {
    private $title;
    private $activePage;

    /**
     * @param string $title The window title of the current view page
     * @param string $activePage Slug identifier to toggle navigation highlight state classes
     */
    public function __construct($title, $activePage = 'home') {
        $this->title = $title;
        $this->activePage = $activePage;
    }

    /**
     * Renders the master HTML layout upper scaffold, sticky top navigation bar, and custom CSS injections.
     */
    public function renderHeader() {
        // Scope variables down so they are accessible inside the included file context
        $activePage = $this->activePage;
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title><?php echo htmlspecialchars($this->title); ?></title>
          <!-- Bootstrap 5 CSS CDN -->
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
          <style>
            .bg-dark-custom { background-color: #030b1e; }
            .navbar-custom { background-color: #120705; border-bottom: 1px solid #1e293b; }
            .hero-section {
              position: relative;
              min-height: calc(100vh - 76px);
              background: linear-gradient(to bottom, rgba(3, 11, 30, 0.7), rgba(3, 11, 30, 0.9)), url('assets/images/ggs.avif') no-repeat center center;
              background-size: cover;
            }
            .btn-brand-green {
              background-color: #5ce65c; color: #0f172a; font-weight: 700; border: none; transition: all 0.2s ease-in-out;
            }
            .btn-brand-green:hover { background-color: #4cd04c; color: #0f172a; transform: scale(1.03); }
            .logo-box {
              background: linear-gradient(135deg, #2563eb, #10b981);
              width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
              color: white; font-weight: bold; font-size: 1.25rem; border-radius: 4px;
            }
            .navbar-custom .navbar-nav .nav-link { transition: color 0.15s ease-in-out; }
            .navbar-custom .navbar-nav .nav-link:hover, .navbar-custom .navbar-nav .nav-link.active { color: #5ce65c !important; }
            
            /* High contrast forms and cards corresponding to design visuals */
            .plan-card { background: white; border-radius: 20px; color: #0f172a; transition: transform 0.2s; }
            .plan-card.popular { border: 4px solid #5ce65c; }
            .plan-card-header-green { background-color: #5ce65c; color: #0f172a; border-radius: 14px 14px 0 0; font-weight: bold; }
            .form-container-white { background: white; border-radius: 16px; color: #0f172a; padding: 2.5rem; }
          </style>
        </head>
        <body class="bg-dark-custom text-white d-flex flex-column min-vh-100">

          <!-- Dynamic Navbar layout component module render -->
          <?php include __DIR__ . '/navbar.php'; ?>

        <?php
    }

    /**
     * Renders the master closure layout tag footprint, global footer information block, and Bootstrap JS drivers.
     */
    public function renderFooter() {
        ?>
            <!-- Global Stick-to-bottom Footnote Layout Structure -->
            <footer class="w-100 text-center py-4 text-secondary mt-auto" style="font-size: 0.75rem; border-top: 1px solid rgba(255,255,255,0.05);">
              &copy; <?php echo date("Y"); ?> Syntrix. All Rights Reserved
            </footer>
          <!-- Bootstrap 5 Bundle with Popper JS CDN -->
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    }
}
?>