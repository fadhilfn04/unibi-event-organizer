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
          <button type="button" class="btn btn-default btn-flat row-action-button" id="set-nilai-button" data-shortcut="F1">
            <i class="fa fa-check mr-1"></i>
            Set Nilai
          </button>
          <button type="button" class="btn btn-default btn-flat row-action-button" id="set-keaktifan-button" data-shortcut="F2">
            <i class="fa fa-check mr-1"></i>
            Set Keaktifan
          </button>
          <button type="button" class="btn btn-default btn-flat row-action-button" id="sertifikat-button" data-shortcut="F3">
            <i class="fas fa-certificate mr-1"></i>
            Sertifikat
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
        <a class="btn btn-info btn-sm" href="<?= base_url() ?>agenda/index/<?= $id_event ?>">Agenda</a>
        <!-- <a class="btn btn-info btn-sm" href="<?= base_url() ?>peserta/index/<?= $id_event ?>">Peserta</a> -->
        <a class="btn btn-info btn-sm" href="<?= base_url() ?>pendaftaran/index/<?= $id_event ?>">Pendaftaran</a>
        <a class="btn btn-info btn-sm" href="<?= base_url() ?>kehadiran/index/<?= $id_event ?>">Kehadiran</a>
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
                  <th style="width: 110px;">Kehadiran</th>
                  <th style="width: 110px;">Nilai</th>
                  <th style="width: 110px;">Keaktifan</th>
                  <th style="width: 110px;">Dapat Sertifikat?</th>
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

<div class="modal fade form-modal" id="form-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="action-form" action="javascript:;" method="post">
        <div class="modal-body">
          <input type="hidden" name="id" value="">
          <input type="hidden" name="type" value="set-nilai">
          <div class="form-group">
            <label for="input-nilai">Nilai</label>
            <input type="text" class="form-control" id="input-nilai" placeholder="Nilai" name="nilai">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-success">
            <i class="fa fa-check mr-1"></i>
            OK
          </button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>