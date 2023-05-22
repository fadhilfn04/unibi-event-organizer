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
        <?php if($this->session->userdata('role') <> ROLE_PETUGAS){ ?>
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
        <?php } ?>
          <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-ellipsis-v mr-1"></i>
              Lainnya
            </button>
            <div class="dropdown-menu dropdown-menu-right">
            <?php if($this->session->userdata('role') == ROLE_SUPERADMIN){ ?>
              <button type="button" class="dropdown-item row-action-button" id="agenda-button">
                Agenda
              </button>
            <?php } ?>
            <?php if($this->session->userdata('role') == ROLE_SUPERADMIN){ ?>
              <button type="button" class="dropdown-item row-action-button" disabled="disabled" id="peserta-button">
                Peserta
              </button>
            <?php } ?>
            <?php if($this->session->userdata('role') <> ROLE_ADMIN){ ?>
              <button type="button" class="dropdown-item row-action-button" disabled="disabled" id="kehadiran-button">
                Kehadiran
              </button>
            <?php } ?>
            <?php if($this->session->userdata('role') <> ROLE_PETUGAS){ ?>
              <button type="button" class="dropdown-item row-action-button" disabled="disabled" id="pendaftaran-button">
                Pendaftaran
              </button>
            <?php } ?>
            </div>
          </div>
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
                  <th style="position: sticky; top:0;" class="bg-white all">Kategori</th>
                  <th style="position: sticky; top:0;" class="bg-white all">Nama</th>
                  <th style="position: sticky; top:0;" class="bg-white all">Tema</th>
                  <th style="position: sticky; top:0;" class="bg-white all">Mulai</th>
                  <th style="position: sticky; top:0;" class="bg-white all">Selesai</th>
                  <th style="position: sticky; top:0;" class="bg-white all">Harga</th>
                  <th style="position: sticky; top:0;" class="bg-white all">Status</th>
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
      <form id="action-form" action="javascript:;" method="post" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" name="id" value="">
          <input type="hidden" name="type" value="">
          <h4 class="text-info">Informasi Event</h4>
          <hr class="mt-2 mb-2">
          <img id="img-cover" src="<?= base_url() ?>assets/images/placeholder-image.png" class="px-2 mb-2 img-fluid" alt="Cover image" style="max-height: 240px;">
          <div class="ml-2 form-group" id="container-file_cover">
            <label for="input-file_cover">Cover <small>(max. 2MB)</small></label>
            <div class="input-group">
              <input type="hidden" id="hidden-file_cover" name="file_cover">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="input-file_cover" accept="image/jpeg,image/png">
                <label class="custom-file-label" for="input-file_cover">Choose file</label>
              </div>
              <div class="input-group-append">
                <button type="button" class="btn btn-default upload-button">
                  <i class="fa fa-upload mr-1"></i>
                  Upload
                </button>
              </div>
            </div>
          </div>
          <div class="ml-2 form-group">
            <label for="input-npm">Nama</label>
            <input type="text" class="form-control" id="input-nama" placeholder="Nama" name="nama">
          </div>
          <div class="ml-2 form-group">
            <label for="input-tema">Tema</label>
            <input type="text" class="form-control" id="input-tema" placeholder="Tema" name="tema">
          </div>
          <div class="ml-2 form-group">
            <label for="input-kategori">Kategori</label>
            <select class="form-control select-kategori" name="id_kategori">
              <option value="">- Pilih Kategori -</option>
              <?php foreach($kategori_list as $item): ?>
                <option value="<?= $item->id ?>"><?= $item->nama ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="ml-2 form-group">
            <label>Durasi</label>
            <div class="input-group">
              <input type="text" class="form-control datetimepicker-input" id="input-tanggal_mulai" data-toggle="datetimepicker" data-target="#input-tanggal_mulai" name="tanggal_mulai">
              <div class="input-group-prepend input-group-append">
                <span class="input-group-text">s/d</span>
              </div>
              <input type="text" class="form-control datetimepicker-input" id="input-tanggal_selesai" data-toggle="datetimepicker" data-target="#input-tanggal_selesai" name="tanggal_selesai">
            </div>
          </div>
          <div class="ml-2 form-group">
            <label for="input-harga">Harga</label>
            <input type="number" class="form-control" id="input-harga" placeholder="Harga" name="harga">
          </div>
          <div class="ml-2 mb-2 form-group">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input custom-control-input-info" type="checkbox" id="input-check_bersertifikat" value="1" name="bersertifikat">
              <label for="input-check_bersertifikat" class="custom-control-label">Bersertifikat</label>
            </div>
          </div>
          <img id="img-sertifikat" src="<?= base_url() ?>assets/images/placeholder-image.png" class="px-2 mb-2 img-fluid" alt="Cover image" style="max-height: 240px;">
          <div class="ml-2 form-group" id="container-file_sertifikat">
            <label for="input-file_sertifikat">Background Sertifikat <small>(max. 2MB; 1280x820)</small></label>
            <div class="input-group">
              <input type="hidden" id="hidden-file_sertifikat" name="file_sertifikat">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="input-file_sertifikat" accept="image/jpeg,image/png">
                <label class="custom-file-label" for="input-file_sertifikat">Choose file</label>
              </div>
              <div class="input-group-append">
                <button type="button" class="btn btn-default upload-button">
                  <i class="fa fa-upload mr-1"></i>
                  Upload
                </button>
              </div>
            </div>
          </div>

          <h4 class="text-info">Penanggung Jawab</h4>
          <hr class="mt-2 mb-2">
          <button type="button" id="add-penanggung-jawab-button" class="ml-2 mb-4 btn btn-default btn-sm">
            <i class="fas fa-plus mr-1"></i> Tambah
          </button>
          <div id="penanggung-jawab-container" class="ml-2">
          </div>

          <h4 class="text-info">Syarat Sertifikat</h4>
          <hr class="mt-2 mb-2">
          <div class="ml-2 mb-2 form-group">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input custom-control-input-info" type="checkbox" id="input-check_nilai_minimum">
              <label for="input-check_nilai_minimum" class="custom-control-label">Nilai minimum</label>
            </div>
          </div>
          <div class="ml-2 form-group">
            <input type="text" class="form-control" id="input-nilai_minimum" placeholder="Nilai minimum" name="nilai_minimum">
          </div>
          <div class="ml-2 mb-2 form-group">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input custom-control-input-info" type="checkbox" id="input-check_kehadiran_minimum">
              <label for="input-check_kehadiran_minimum" class="custom-control-label">Kehadiran minimum (%)</label>
            </div>
          </div>
          <div class="ml-2 form-group">
            <input type="text" class="form-control" id="input-kehadiran_minimum" placeholder="Kehadiran minimum" name="kehadiran_minimum">
          </div>
          <div class="ml-2 mb-2 form-group">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input custom-control-input-info" type="checkbox" id="input-keaktifan" name="keaktifan" value="1">
              <label for="input-keaktifan" class="custom-control-label">Keaktifan</label>
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