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
        <div class="card card-outline card-info">
          <div class="card-body">
            <table id="datatable" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width: 50px;">No.</th>
                  <th style="width: 120px;">NPM</th>
                  <th>Nama Mahasiswa</th>
                  <th>Email</th>
                  <th style="width: 180px;">Jurusan</th>
                  <th>No. Telepon</th>
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
            <label for="input-npm">NPM</label>
            <input type="text" class="form-control" id="input-npm" placeholder="NPM" name="npm">
          </div>
          <div class="form-group">
            <label for="input-nama">Nama Mahasiswa</label>
            <input type="text" class="form-control" id="input-nama" placeholder="Nama" name="nama">
          </div>
          <div class="form-group">
            <label for="input-email">Email</label>
            <input type="text" class="form-control" id="input-email" placeholder="Email" name="email">
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="input-id_fakultas">Fakultas</label>
                <select type="text" class="form-control" id="input-id_fakultas" name="id_fakultas">
                  <option value="" selected disabled>- Pilih Fakultas -</option>
                  <?php foreach($fakultas_list as $item): ?>
                    <option value="<?= $item->id ?>"><?= $item->nama ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="input-id_jurusan">Jurusan</label>
                <select type="text" class="form-control" id="input-id_jurusan" name="id_jurusan">
                  <option value="" selected disabled>- Pilih Jurusan -</option>
                  <?php foreach($jurusan_list as $item): ?>
                    <option value="<?= $item->id ?>"><?= $item->nama ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="input-no_telepon">No. Telepon</label>
                <input type="text" class="form-control" id="input-no_telepon" placeholder="No. Telepon" name="no_telepon">
              </div>
            </div>
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