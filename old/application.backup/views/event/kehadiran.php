<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-3">
        <h1 class="m-0 text-dark">
          <?= $title ?>
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-9">
        <div class="btn-group float-sm-right">
          <?php $disabled = $tanggal != date('Y-m-d') ? 'disabled="disabled"' : ''; ?>
          <button type="button" class="btn btn-default btn-flat" id="qrcode-button" data-shortcut="F1" <?= $disabled ?>>
            <i class="fas fa-qrcode mr-1"></i>
            QR Code
          </button>
          <button type="button" class="btn btn-default btn-flat row-action-button" id="set-hadir-button" data-shortcut="F2">
            <i class="fa fa-check mr-1"></i>
            <span id="set-hadir-text">Set Kehadiran</span>
          </button>
          <button type="button" class="btn btn-default btn-flat row-action-button" id="set-ijin-button" data-shortcut="F3">
            <i class="fa fa-check mr-1"></i>
            <span id="set-hadir-text">Set Ijin</span>
          </button>
          <button type="button" class="btn btn-default btn-flat" id="refresh-button" data-shortcut="F4">
            <i class="fa fa-sync-alt mr-1"></i>
            Refresh
          </button>
        </div>
      </div>
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <a class="btn btn-default btn-sm" href="<?= base_url() ?>event">Kembali</a>
        <a class="btn btn-info btn-sm" href="<?= base_url() ?>agenda/index/<?= $id_event ?>">Agenda</a>
        <a class="btn btn-info btn-sm" href="<?= base_url() ?>peserta/index/<?= $id_event ?>">Peserta</a>
        <a class="btn btn-info btn-sm" href="<?= base_url() ?>pendaftaran/index/<?= $id_event ?>">Pendaftaran</a>
        <!-- <a class="btn btn-info btn-sm" href="<?= base_url() ?>kehadiran/index/<?= $id_event ?>">Kehadiran</a> -->
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-md-12">
        <div class="card card-outline card-info">
          <div class="card-body">
            <table id="datatable" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width: 40px;">No.</th>
                  <th>NPM</th>
                  <th>Nama</th>
                  <th style="width: 200px;">Hadir</th>
                  <th style="width: 110px;">Jam Absen</th>
                  <th style="width: 110px;">Cara Absen</th>
                  <th style="width: 110px;">Ijin</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->