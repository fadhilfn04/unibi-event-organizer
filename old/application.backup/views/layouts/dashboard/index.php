<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
<?php $this->load->view('layouts/dashboard/top-scripts.php'); ?>
</head>
<body class="hold-transition sidebar-mini control-sidebar-slide-open accent-info">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-info border-bottom-0">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="javascript:;" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= site_url('auth/ubah-password') ?>" class="nav-link">
          <i class="fa fa-key mr-1"></i>
          Ubah Password
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a id="logout-button" href="<?= site_url('auth/logout') ?>" class="nav-link">
          <i class="fa fa-sign-out-alt mr-1"></i>
          Logout
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar elevation-4 sidebar-dark-info">
    <!-- Brand Logo -->
    <a href="<?= site_url() ?>" class="brand-link">
      <img src="<?= base_url('assets') ?>/images/logo-square.png" alt="App Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Event Organizer</span>
    </a>
    <?php $this->load->view('layouts/dashboard/sidebar.php'); ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php $this->load->view($content); ?>
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view('layouts/dashboard/footer.php'); ?>
</div>
<!-- ./wrapper -->

<div class="modal fade" id="logout-modal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-sign-out-alt mr-1"></i> Logout</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('auth/logout') ?>" method="post">\
        <div class="modal-body">
          <p>Keluar dari aplikasi?</p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
          <button type="submit" class="btn btn-primary">Ya</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- REQUIRED SCRIPTS -->
<?php $this->load->view('layouts/dashboard/bottom-scripts.php'); ?>
</body>
</html>
