<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          <?= $title ?>
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <div class="btn-group float-sm-right">
          <button type="button" class="btn btn-default btn-flat" id="add-button" data-shortcut="F1">
            <i class="fa fa-plus mr-1"></i>
            Tambah Data
          </button>
          <button type="button" class="btn btn-default btn-flat row-action-button" id="edit-button" disabled="disabled" data-shortcut="F2">
            <i class="fa fa-pencil-alt mr-1"></i>
            Ubah Data
          </button>
          <button type="button" class="btn btn-default btn-flat row-action-button" id="remove-button" disabled="disabled" data-shortcut="F3">
            <i class="fa fa-eraser mr-1"></i>
            Hapus Data
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
        <?php if($this->session->userdata('role') == ROLE_SUPERADMIN){ ?>
        <!-- <a class="btn btn-info btn-sm" href="<?= base_url() ?>agenda/index/<?= $id_event ?>">Agenda</a> -->
        <a class="btn btn-info btn-sm" href="<?= base_url() ?>peserta/index/<?= $id_event ?>">Peserta</a>
        <a class="btn btn-info btn-sm" href="<?= base_url() ?>pendaftaran/index/<?= $id_event ?>">Pendaftaran</a>
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
                  <th style="width: 50px;">No.</th>
                  <th style="width: 100px;">Tanggal</th>
                  <th style="width: 100px;">Mulai</th>
                  <th style="width: 100px;">Selesai</th>
                  <th>Kegiatan</th>
                  <th>Narasumber</th>
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
          <input type="hidden" name="type" value="">
          <div class="form-group">
            <label for="input-tanggal">Tanggal</label>
            <select id="input-tanggal" class="form-control" name="tanggal">
              <?php foreach($dates as $date): ?>
              <option value="<?= $date ?>"><?= to_date_format('d/m/Y', $date) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Durasi</label>
            <div class="input-group">
              <input type="text" class="form-control datetimepicker-input" id="input-jam_mulai" data-toggle="datetimepicker" data-target="#input-jam_mulai" name="jam_mulai">
              <div class="input-group-prepend input-group-append">
                <span class="input-group-text">s/d</span>
              </div>
              <input type="text" class="form-control datetimepicker-input" id="input-jam_selesai" data-toggle="datetimepicker" data-target="#input-jam_selesai" name="jam_selesai">
            </div>
          </div>
          <div class="form-group">
            <label for="input-kegiatan">Kegiatan</label>
            <input type="text" class="form-control" id="input-kegiatan" placeholder="Kegiatan" name="kegiatan">
          </div>
          <div class="form-group">
            <label for="input-narasumber">Narasumber</label>
            <input type="text" class="form-control" id="input-narasumber" placeholder="Narasumber" name="narasumber">
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