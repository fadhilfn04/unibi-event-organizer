<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

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
              <table id="datatable" class="table table-separate table-head-custom table-checkable table-default" style="width: 100%;">
                <thead>
                  <tr>
                    <th style="position: sticky; top:0;" class="bg-white all">No.</th>
                    <th style="position: sticky; top:0;" class="bg-white all">Nama</th>
                    <th style="position: sticky; top:0;" class="bg-white all">Pekerjaan</th>
                    <th style="position: sticky; top:0;" class="bg-white all">Bidang Keahlian</th></th>
                    <th style="position: sticky; top:0;" class="bg-white all">Pendidikan</th>
                    <th style="position: sticky; top:0;" class="bg-white all">Email</th>
                    <th style="position: sticky; top:0;" class="bg-white all">No. Telepon</th>
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
            <label for="input-nama">Nama</label>
            <input type="text" class="form-control" id="input-nama" placeholder="Nama" name="nama">
          </div>
          <div class="form-group">
            <label for="input-pekerjaan">Pekerjaan</label>
            <input type="text" class="form-control" id="input-pekerjaan" placeholder="Pekerjaan" name="pekerjaan">
          </div>
          <div class="form-group">
            <label for="input-keahlian">Bidang Keahlian</label>
            <input type="text" class="form-control" id="input-keahlian" placeholder="keahlian" name="keahlian">
          </div>
          <div class="form-group">
            <label for="input-pendidikan">Pendidikan</label>
            <input type="text" class="form-control" id="input-pendidikan" placeholder="Pendidikan" name="pendidikan">
          </div>
          <div class="form-group">
            <label for="input-email">Email</label>
            <input type="text" class="form-control" id="input-email" placeholder="Email" name="email">
          </div>
          <div class="form-group">
            <label for="input-no_telepon">No. Telepon</label>
            <input type="number" class="form-control" id="input-no_telepon" placeholder="No. Telepon" name="no_telepon">
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