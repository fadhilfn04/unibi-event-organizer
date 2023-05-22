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
          <button type="button" class="btn btn-default btn-flat row-action-button" id="detail-button" data-shortcut="F6">
            <i class="fa fa-search mr-1"></i>
            Detail
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
        <div class="card card-outline card-danger">
          <div class="card-body">
            <table id="datatable" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width: 50px;">No.</th>
                  <th>Kode</th>
                  <th>Nama Voting</th>
                  <th>Waktu Mulai</th>
                  <th>Waktu Selesai</th>
                  <th>Keterangan</th>
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
  <div class="modal-dialog modal-lg">
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
            <label for="input-kode">Kode</label>
            <input type="text" class="form-control" id="input-kode" placeholder="Kode" name="kode">
          </div>
          <div class="form-group">
            <label for="input-nama">Nama Voting</label>
            <input type="text" class="form-control" id="input-nama" placeholder="Nama" name="nama">
          </div>
          <div class="form-group">
            <label for="input-tanggal_mulai">Waktu Mulai</label>
            <input type="text" class="form-control datetimepicker-input" id="input-tanggal_mulai" data-toggle="datetimepicker" data-target="#input-tanggal_mulai" name="tanggal_mulai">
          </div>
          <div class="form-group">
            <label for="input-tanggal_selesai">Waktu Selesai</label>
            <input type="text" class="form-control datetimepicker-input" id="input-tanggal_selesai" data-toggle="datetimepicker" data-target="#input-tanggal_selesai" name="tanggal_selesai">
          </div>
          <div class="form-group">
            <label for="input-keterangan">Keterangan</label>
            <textarea class="form-control" rows="3" id="input-keterangan" name="keterangan"></textarea>
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