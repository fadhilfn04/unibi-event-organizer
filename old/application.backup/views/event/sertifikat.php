<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-3">
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
  <div class="container-fluid">
    <div class="row mt-2">
      <div class="col-md-12">
        <div class="card card-outline card-info">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <form id="form-search" action="javascript:;" method="POST">
                  <div class="row">
                    <div class="col-xs-12 col-lg-3 col-md-2">
                      <div class="form-group">
                        <label for="input-kode">Masukkan Kode Sertifikat</label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="input-kode" placeholder="Kode" name="kode" value="<?= $kode ?>">
                          <div class="input-group-append">
                            <button class="btn btn-primary" name="action" type="submit">
                              <i class="fa fa-search"></i> Cari
                            </button>
                          </div>
                        </div>
                        <?php if(!empty($kode) && !$has_sertifikat): ?>
                        <span class="text-danger">Sertifikat tidak ditemukan</span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <?php if($has_sertifikat): ?>
            <div class="row">
              <div class="col-12">
                <object style="width: 100%; height: 1000px;" data="<?= site_url('files/sertifikat/' . $kode) ?>" type="application/pdf"></object>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>