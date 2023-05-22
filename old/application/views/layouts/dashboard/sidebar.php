<?php
$nav_active = function($name, $active_open = FALSE) use ($nav) {
  return in_array($name, (array) $nav) ? 'active' : '';
};
$nav_open = function($name, $active_open = FALSE) use ($nav) {
  return in_array($name, (array) $nav) ? 'menu-open' : '';
};
?>
<!-- Sidebar -->
<div class="sidebar">
  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-legacy" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item">
        <a href="<?= site_url() ?>" class="nav-link <?= $nav_active('beranda') ?>">
          <i class="nav-icon fas fa-home"></i>
          <p>Beranda</p>
        </a>
      </li>

      <!-- <?php if($this->session->userdata('role') == ROLE_SUPERADMIN){ ?>
        <li class="nav-item">
          <a href="<?= site_url() ?>master/mahasiswa" class="nav-link <?= $nav_active('mahasiswa') ?>">
            <i class="fas fa-users nav-icon"></i>
            <p>Pengguna / Mahasiswa</p>
          </a>
        </li>
      <?php } ?> -->

      <?php if($this->session->userdata('role') == ROLE_SUPERADMIN){ ?>
        <li class="nav-item has-treeview <?= $nav_open('pengguna') ?>">
          <a href="javascript:;" class="nav-link <?= $nav_active('pengguna') ?>">
            <i class="nav-icon fas fa-database"></i>
            <p>
              Pengguna
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= site_url() ?>master/mahasiswa" class="nav-link <?= $nav_active('mahasiswa') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Mahasiswa</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url() ?>master/narasumber" class="nav-link <?= $nav_active('narasumber') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Narasumber</p>
              </a>
            </li>
          </ul>
        </li>
      <?php } ?>

      <?php if($this->session->userdata('role') == ROLE_SUPERADMIN || ROLE_ADMIN){ ?>
        <li class="nav-item">
          <a href="<?= site_url() ?>event" class="nav-link <?= $nav_active('event') ?>">
            <i class="fas fa-vote-yea nav-icon"></i>
            <p>Data Event</p>
          </a>
        </li>
      <?php } ?>
      
      <?php if($this->session->userdata('role') == ROLE_SUPERADMIN || ROLE_ADMIN){ ?>
        <li class="nav-item">
          <a href="<?= site_url() ?>pesertaEvent" class="nav-link <?= $nav_active('pesertaEvent') ?>">
            <i class="fas fa-vote-yea nav-icon"></i>
            <p>Data Peserta Event</p>
          </a>
        </li>
      <?php } ?>

      <?php if($this->session->userdata('role') == ROLE_SUPERADMIN){ ?>
        <li class="nav-item">
          <a href="<?= site_url() ?>sertifikat" class="nav-link <?= $nav_active('sertifikat') ?>">
            <i class="fas fa-certificate nav-icon"></i>
            <p>Sertifikat</p>
          </a>
        </li>
      <?php } ?>

      <?php if($this->session->userdata('role') == ROLE_SUPERADMIN){ ?>
        <li class="nav-item has-treeview <?= $nav_open('master') ?>">
          <a href="javascript:;" class="nav-link <?= $nav_active('master') ?>">
            <i class="nav-icon fas fa-database"></i>
            <p>
              Master
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= site_url() ?>master/jurusan" class="nav-link <?= $nav_active('jurusan') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Jurusan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url() ?>master/fakultas" class="nav-link <?= $nav_active('fakultas') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Fakultas</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url() ?>master/kategori-event" class="nav-link <?= $nav_active('kategori-event') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Kategori Event</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url() ?>master/users" class="nav-link <?= $nav_active('users') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Users</p>
              </a>
            </li>
          </ul>
        </li>
      <?php } ?>
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->