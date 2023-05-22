<?php
$container = 'container-fluid';
if($this->session->userdata('role') == ROLE_MAHASISWA)
  $container = 'container';
?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="<?= $container ?>">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          <?= $title ?>
        </h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="<?= $container ?>">
    <div class="row">
      <div class="col-md-6">
        <?php if($this->session->flashdata('has_alert')): ?>
        <div class="alert alert-<?= $this->session->flashdata('alert_type') ?> alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <?= $this->session->flashdata('alert_message') ?>
        </div>
        <?php endif; ?>
        <div class="card card-primary card-outline">
          <form method="post">
            <div class="card-body">
              <div class="form-group">
                <label for="input-field-old_password">Password Lama</label>
                <input type="password" class="form-control" id="input-field-old_password" placeholder="Password" name="old_password">
              </div>
              <div class="form-group">
                <label for="input-field-password">Password Baru</label>
                <input type="password" class="form-control" id="input-field-password" placeholder="Password" name="password">
              </div>
              <div class="form-group">
                <label for="input-field-confirm_password">Ulangi Password Baru</label>
                <input type="password" class="form-control" id="input-field-confirm_password" placeholder="Password" name="confirm_password">
              </div>
              <!-- <div class="form-group">
                <label for="input-field-test_period">Period</label>
                <input type="text" class="form-control datetimepicker-input"
                  data-toggle="datetimepicker"
                  data-target="#form-field-test_period"
                  id="form-field-test_period"
                  name="test_period">
              </div>
              <div class="form-group">
                <label for="input-field-test_date">Date</label>
                <input type="text" class="form-control datetimepicker-input"
                  data-toggle="datetimepicker"
                  data-target="#form-field-test_date"
                  id="form-field-test_date"
                  name="test_date">
              </div> -->
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-check"></i>
                OK
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->