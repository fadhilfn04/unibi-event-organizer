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
          <button type="button" class="btn btn-default btn-flat row-action-button" id="verifikasi-button" data-shortcut="F1">
            <i class="fa fa-check mr-1"></i>
            Verifikasi
          </button>
          <button type="button" class="btn btn-default btn-flat row-action-button" id="remove-button" disabled="disabled" data-shortcut="F2">
            <i class="fa fa-eraser mr-1"></i>
            Hapus Data
          </button>
          <button type="button" class="btn btn-default btn-flat" id="refresh-button" data-shortcut="F3">
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
        <?php if($this->session->userdata('role') == ROLE_SUPERADMIN){ ?>
          <a class="btn btn-info btn-sm" href="<?= base_url() ?>agenda/index/<?= $id_event ?>">Agenda</a>
          <a class="btn btn-info btn-sm" href="<?= base_url() ?>peserta/index/<?= $id_event ?>">Peserta</a>
          <a class="btn btn-info btn-sm" href="<?= base_url() ?>kehadiran/index/<?= $id_event ?>">Kehadiran</a>
        <?php } ?>
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
                  <th style="width: 200px;">Jurusan</th>
                  <th style="width: 110px;">Bukti Transfer</th>
                  <th style="width: 110px;">Tgl Transfer</th>
                  <th>Status</th>
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