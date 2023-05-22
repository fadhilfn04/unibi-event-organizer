<script>
window.dataVoting = <?= json_encode($data_voting) ?>;
</script>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12">
        <h1 class="m-0 text-dark">
          <?= $title ?>
        </h1>
      </div><!-- /.col -->
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-3">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-info-circle"></i>
              Informasi
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <strong><i class="fas fa-code mr-1"></i> Kode</strong>
            <p class="text-muted"><?= $data_voting->kode ?></p>

            <hr>

            <strong><i class="fas fa-vote-yea mr-1"></i> Nama Voting</strong>
            <p class="text-muted"><?= $data_voting->nama ?></p>

            <hr>

            <strong><i class="far fa-clock mr-1"></i> Waktu &amp; Tanggal Acara</strong>
            <p class="text-muted">
              <?php
                $date_start = new \DateTime($data_voting->tanggal_mulai);
                $date_end   = new \DateTime($data_voting->tanggal_selesai);
              ?>
              <p>
                <span class="badge badge-secondary"><?= $date_start->format('d/m/y - H:i') ?></span>
                s/d
                <span class="badge badge-secondary"><?= $date_end->format('d/m/y - H:i') ?></span>
              </p>
            </p>

            <hr>

            <strong><i class="far fa-calendar mr-1"></i> Status</strong>
            <?php if(date('Y-m-d H:i:s') > $data_voting->tanggal_selesai): ?>
              <p class="text-muted">
                Sudah Selesai
              </p>
            <?php elseif(date('Y-m-d H:i:s') < $data_voting->tanggal_mulai): ?>
              <p class="text-muted">
                Belum Dimulai
              </p>
            <?php else: ?>
              <p class="text-muted">
                Sedang Berlangsung
              </p>
            <?php endif; ?>
          </div>
          <!-- /.card-body -->
        </div>
        <div class="small-box bg-info">
          <div id="kandidat-count-overlay" class="overlay" style="display: none;">
            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
          </div>
          <div class="inner">
            <h3 id="kandidat-count"><?= $kandidat_count ?></h3>
            <p>Kandidat</p>
          </div>
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
          <a href="javascript:;" id="refresh-kandidat-count" class="small-box-footer">Refresh</a>
        </div>
        <div class="small-box bg-blue">
          <div id="pemilih-count-overlay" class="overlay" style="display: none;">
            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
          </div>
          <div class="inner">
            <h3 id="pemilih-count"><?= $pemilih_count ?></h3>
            <p>Calon Pemilih</p>
          </div>
          <div class="icon">
            <i class="fas fa-user-check"></i>
          </div>
          <a href="javascript:;" id="refresh-pemilih-count" class="small-box-footer">Refresh</a>
        </div>
        <div class="small-box bg-yellow">
          <div id="suara-count-overlay" class="overlay" style="display: none;">
            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
          </div>
          <div class="inner">
            <h3 id="suara-count"><?= $suara_count ?></h3>
            <p>Suara Masuk</p>
          </div>
          <div class="icon">
            <i class="fas fa-vote-yea"></i>
          </div>
          <a href="javascript:;" id="refresh-suara-count" class="small-box-footer">Refresh</a>
        </div>
      </div>
      <div class="col-sm-9">
        <div class="card card-primary card-tabs">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="detail-voting" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="detail-kandidat-tab" data-toggle="pill" href="#detail-kandidat" role="tab" aria-controls="detail-kandidat" aria-selected="true">
                  Daftar Kandidat
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="detail-pemilih-tab" data-toggle="pill" href="#detail-pemilih" role="tab" aria-controls="detail-pemilih" aria-selected="false">
                  Daftar Pemilih
                </a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="detail-voting-content">
              <div class="tab-pane fade show active" id="detail-kandidat" role="tabpanel" aria-labelledby="detail-kandidat-tab">
                <div class="row">
                  <div class="col-sm-6">
                    <form class="form-inline">
                      <div class="input-group search-form" data-target="table-kandidat">
                        <input type="text" class="form-control" placeholder="Search">
                        <div class="input-group-append">
                          <button type="button" class="btn btn-default">
                            <i class="fa fa-search"></i>
                            Search
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-sm-6">
                    <div class="btn-group float-sm-right">
                      <button type="button" class="btn btn-default btn-flat add-button" data-table="table-kandidat">
                        <i class="fa fa-plus mr-1"></i>
                        Tambah
                      </button>
                      <button type="button" class="btn btn-default btn-flat row-action-button edit-button" data-table="table-kandidat" disabled="disabled">
                        <i class="fa fa-pencil-alt mr-1"></i>
                        Ubah
                      </button>
                      <button type="button" class="btn btn-default btn-flat row-action-button remove-button" data-table="table-kandidat" disabled="disabled">
                        <i class="fa fa-eraser mr-1"></i>
                        Hapus
                      </button>
                      <button type="button" class="btn btn-default btn-flat refresh-button" data-table="table-kandidat">
                        <i class="fa fa-sync-alt mr-1"></i>
                        Refresh
                      </button>
                      <button type="button" class="btn btn-default btn-flat row-action-button detail-button" data-table="table-kandidat" disabled="disabled">
                        <i class="fa fa-eye mr-1"></i>
                        Detail
                      </button>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-sm-12">
                    <table id="table-kandidat" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th style="width: 50px;">No.</th>
                          <th>NPM</th>
                          <th>Nama</th>
                          <th>Angkatan</th>
                          <th>Jurusan</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="detail-pemilih" role="tabpanel" aria-labelledby="detail-pemilih-tab">
                <div class="row">
                  <div class="col-sm-6">
                    <form class="form-inline search-form" data-target="table-pemilih">
                      <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search">
                        <div class="input-group-append">
                          <button type="button" class="btn btn-default">
                            <i class="fa fa-search"></i>
                            Search
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-sm-6">
                    <div class="btn-group float-sm-right">
                      <button type="button" class="btn btn-default btn-flat add-button" data-table="table-pemilih">
                        <i class="fa fa-plus mr-1"></i>
                        Tambah
                      </button>
                      <button type="button" class="btn btn-default btn-flat row-action-button remove-button" data-table="table-pemilih" disabled="disabled">
                        <i class="fa fa-eraser mr-1"></i>
                        Hapus
                      </button>
                      <button type="button" class="btn btn-default btn-flat remove-batch-button" data-table="table-pemilih">
                        <i class="fa fa-eraser mr-1"></i>
                        Hapus Banyak
                      </button>
                      <button type="button" class="btn btn-default btn-flat refresh-button" data-table="table-pemilih">
                        <i class="fa fa-sync-alt mr-1"></i>
                        Refresh
                      </button>
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-sm-12">
                    <table id="table-pemilih" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th style="width: 50px;">No.</th>
                          <th>NPM</th>
                          <th>Nama</th>
                          <th>Angkatan</th>
                          <th>Jurusan</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade form-modal" id="form-kandidat-modal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="kandidat-action-form" action="javascript:;" method="post">
        <div class="modal-body">
          <input type="hidden" name="id" value="">
          <input type="hidden" name="type" value="">
          <div class="row">
            <div class="col-sm-6 text-center">
              <img id="img-foto" class="img-fluid" src="<?= base_url() ?>assets/images/placeholder-image.png">
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="input-id_mahasiswa">Mahasiswa</label>
                <select class="form-control" id="input-id_mahasiswa" name="id_mahasiswa"></select>
              </div>
              <div class="form-group">
                <label for="input-npm">NPM</label>
                <input type="text" readonly="readonly" class="form-control" id="input-npm" name="npm">
              </div>
              <div class="form-group">
                <label for="input-jurusan">Jurusan</label>
                <input type="text" readonly="readonly" class="form-control" id="input-jurusan" name="jurusan">
              </div>
              <div class="form-group">
                <label for="input-fakultas">Fakultas</label>
                <input type="text" readonly="readonly" class="form-control" id="input-fakultas" name="fakultas">
              </div>
              <div class="form-group">
                <label>Foto</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="input-foto">
                    <label class="custom-file-label" for="input-foto">Pilih Gambar</label>
                  </div>
                  <div class="input-group-append">
                    <button type="button" id="upload-button" class="btn btn-default">Upload</span>
                  </div>
                </div>
                <input type="hidden" id="hidden-foto" name="foto">
              </div>
              <div class="form-group">
                <label for="input-visi">Visi</label>
                <textarea class="form-control" id="input-visi" placeholder="Visi" name="visi"></textarea>
              </div>
              <div class="form-group">
                <label for="input-misi">Misi</label>
                <textarea class="form-control" id="input-misi" placeholder="Misi" name="misi"></textarea>
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

<div class="modal fade form-modal" id="form-kandidat-modal-detail" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-eye mr-1"></i> Detail Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="kandidat-action-form" action="javascript:;" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-6 text-center">
              <img id="img-foto-detail" class="img-fluid" src="<?= base_url() ?>assets/images/placeholder-image.png">
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="input-nama">Mahasiswa</label>
                <input class="form-control" readonly="readonly" id="input-nama" name="nama">
              </div>
              <div class="form-group">
                <label for="input-npm">NPM</label>
                <input type="text" readonly="readonly" class="form-control" id="input-npm" name="npm">
              </div>
              <div class="form-group">
                <label for="input-jurusan">Jurusan</label>
                <input type="text" readonly="readonly" class="form-control" id="input-jurusan" name="jurusan">
              </div>
              <div class="form-group">
                <label for="input-fakultas">Fakultas</label>
                <input type="text" readonly="readonly" class="form-control" id="input-fakultas" name="fakultas">
              </div>
              <div class="form-group">
                <label for="input-visi">Visi</label>
                <textarea class="form-control" readonly="readonly" id="input-visi" placeholder="Visi" name="visi"></textarea>
              </div>
              <div class="form-group">
                <label for="input-misi">Misi</label>
                <textarea class="form-control" readonly="readonly" id="input-misi" placeholder="Misi" name="misi"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <p></p>
          <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade form-modal" id="form-pemilih-modal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-plus mr-1"></i> Tambah Data</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="pemilih-action-form" action="javascript:;" method="post">
        <div class="modal-body">
          <input type="hidden" name="type" value="">
          <div class="form-group">
            <label for="input-tahun_masuk">Angkatan</label>
            <select multiple="multiple" class="form-control" id="input-tahun_masuk" name="tahun_masuk[]">
            <?php foreach($tahun_masuk_list as $item): ?>
              <option value="<?= $item->tahun_masuk ?>"><?= $item->tahun_masuk ?></option>
            <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="input-id_fakultas">Fakultas</label>
            <select multiple="multiple" class="form-control" id="input-id_fakultas" name="id_fakultas[]">
            <?php foreach($fakultas_list as $item): ?>
              <option value="<?= $item->id ?>"><?= $item->nama ?></option>
            <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="input-id_jurusan">Jurusan</label>
            <select multiple="multiple" class="form-control" id="input-id_jurusan" name="id_jurusan[]">
            <?php foreach($jurusan_list as $item): ?>
              <option value="<?= $item->id ?>"><?= $item->nama ?></option>
            <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="input-id_mahasiswa">Mahasiswa</label>
            <select multiple="multiple" class="form-control" id="input-id_mahasiswa" name="id_mahasiswa[]"></select>
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