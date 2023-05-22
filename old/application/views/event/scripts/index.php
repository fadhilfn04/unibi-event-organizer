<script>
  window.dataKategori = <?= json_encode($kategori_list) ?>;
</script>
<script>
$(document).ready(function() {

  $('#input-tanggal_mulai, #input-tanggal_selesai').datetimepicker({
    locale: 'id',
    format: 'L',
  });

  $('.select-kategori').select2();

  let filterStatus = 'semua', filterKategori = null, penanggungJawabLimit = 0, coverBlobURL = null, sertifikatBlobURL = null;
  const table = $('#datatable').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
    ajax: {
      url: `${baseURL}event/datatable`,
    },
    processing: true,
    serverSide: true,
    scrollX: true,
    scrollY: '400px',
    responsive: {
        details: false
    },
    dom: 'Bflrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    columns: [
      {
        data: 'id',
        render: dtHelper.incrementNumber,
      },
      { data: 'kategori' },
      { data: 'nama' },
      { data: 'tema' },
      {
        data: 'tanggal_mulai',
        render: function(d, t, r) {
          return moment(d).format('L');
        }
      },
      {
        data: 'tanggal_selesai',
        render: function(d, t, r) {
          return moment(d).format('L');
        }
      },
      {
        data: 'harga',
        render: function(d) {
          return `Rp. ${Number(d).toLocaleString('id-ID')}`;
        }
      },
      {
        data: 'status',
        render: function(d, t, r) {
          let tanggalMulai = new Date(r.tanggal_mulai + ' 00:00:00');
          let tanggalSelesai = new Date(r.tanggal_selesai + ' 00:00:00');
          let now = new Date(moment().format('YYYY-MM-DD') + ' 00:00:00');
          if(now < tanggalMulai) {
            return 'Belum mulai';
          }
          else if(tanggalMulai <= now && now <= tanggalSelesai) {
            return 'Sedang berlangsung';
          }
          else if(tanggalSelesai < now) {
            return 'Sudah selesai';
          }
        }
      },
    ],
    dom: "<'row'<'col-sm-12 col-md-6 filter-container'><'col-sm-12 col-md-6'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
  }));

  table.on('select', function () {
    $('.row-action-button').removeAttr('disabled');
  });

  table.on('deselect', function () {
    $('.row-action-button').attr('disabled', 'disabled');
  });

  table.on('draw', function () {
    let selected = table.selectedRow();
    if(selected) {
      $('.row-action-button').removeAttr('disabled');
    }
    else {
      $('.row-action-button').attr('disabled', 'disabled');
    }
  });

  function createFilterKategoriOptions() {
    if(Array.isArray(dataKategori)) {
      let result = '';
      dataKategori.forEach(function (i) {
        result += `<button type="button" class="dropdown-item filter-button" data-value="${i.id}">
          ${i.nama}
        </button>`;
      });
      return result;
    }
  }

  $('#datatable_wrapper .filter-container').html(`
    <div id="filter-status" class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-filter mr-1"></i>
        Status: <span class="filter-text">Semua</span>
        &nbsp;&nbsp;
      </button>
      <div class="dropdown-menu dropdown-menu-left">
        <button type="button" class="dropdown-item filter-button" data-value="semua">
          Semua
        </button>
        <button type="button" class="dropdown-item filter-button" data-value="belum-mulai">
          Belum mulai
        </button>
        <button type="button" class="dropdown-item filter-button" data-value="sedang-berlangsung">
          Sedang berlangsung
        </button>
        <button type="button" class="dropdown-item filter-button" data-value="sudah-selesai">
          Sudah selesai
        </button>
      </div>
    </div>
    <div id="filter-kategori" class="ml-2 btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-filter mr-1"></i>
        Kategori: <span class="filter-text">Semua</span>
        &nbsp;&nbsp;
      </button>
      <div class="dropdown-menu dropdown-menu-left">
        <button type="button" class="dropdown-item filter-button" data-value="">
          Semua
        </button>
        ${createFilterKategoriOptions()}
      </div>
    </div>
  `);

  $('#add-penanggung-jawab-button').on('click', function() {
    addPenanggungJawab();
  });

  function addPenanggungJawab() {
    $('#penanggung-jawab-container').append(`
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label>Nama</label>
          <input type="text" class="form-control" placeholder="Nama" name="penanggung_jawab[${penanggungJawabLimit}][nama]">
        </div>
      </div>
      <div class="col-sm-5">
        <div class="form-group">
          <label>Jabatan</label>
          <input type="text" class="form-control" placeholder="Jabatan" name="penanggung_jawab[${penanggungJawabLimit}][jabatan]">
        </div>
      </div>
      <div class="col-sm-1">
        <button type="button" class="btn btn-default remove-button" style="margin-top: 2rem;">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </div>
    `);
    penanggungJawabLimit++;
    if(penanggungJawabLimit >= 3) {
      $('#add-penanggung-jawab-button').attr('disabled', 'disabled');
    }
  }

  $('#penanggung-jawab-container').on('click', '.remove-button', function() {
    $(this).parents('.row').remove();
    penanggungJawabLimit--;
    if(penanggungJawabLimit < 3) {
      $('#add-penanggung-jawab-button').removeAttr('disabled');
    }
  });

  $('#form-modal').on('hidden.bs.modal', function(ev) {
    if(coverBlobURL) {
      URL.revokeObjectURL(coverBlobURL);
      coverBlobURL = null;
      $('#img-cover').attr('src', `${baseURL}assets/images/placeholder-image.png`);
    }
    if(sertifikatBlobURL) {
      URL.revokeObjectURL(sertifikatBlobURL);
      sertifikatBlobURL = null;
      $('#img-sertifikat').attr('src', `${baseURL}assets/images/placeholder-image.png`);
    }
  });

  $('#input-file_cover').on('change', function() {
    if(this.files.length) {
      if(coverBlobURL) {
        URL.revokeObjectURL(coverBlobURL);
        coverBlobURL = null;
      }
      coverBlobURL = URL.createObjectURL(this.files[0]);
      $(this).next('.custom-file-label').text(this.files[0].name)
      $('#img-cover').attr('src', coverBlobURL);
    }
  });

  $('#input-file_sertifikat').on('change', function() {
    if(this.files.length) {
      if(sertifikatBlobURL) {
        URL.revokeObjectURL(sertifikatBlobURL);
        sertifikatBlobURL = null;
      }
      sertifikatBlobURL = URL.createObjectURL(this.files[0]);
      $(this).next('.custom-file-label').text(this.files[0].name)
      $('#img-sertifikat').attr('src', sertifikatBlobURL);
    }
  });

  function doUploadCover() {
    return new Promise((resolve, reject) => {
      let $modal = $('#form-modal');
      const $button = $('#container-file_cover .upload-button');
      const files = $('#input-file_cover')[0].files;
      if(files.length) {
        const originalContent = $button.html();
        $button.attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner mr-1"></i> Mengunggah..');
        $.ajax({
          method: 'post',
          url: `${baseURL}files/upload`,
          contentType: files[0].type,
          data: files[0],
          processData: false,
        })
        .done(function(data) {
          console.log(data);
          $modal.find('[name=file_cover]').val(data.key);
        })
        .always(function() {
          $button.removeAttr('disabled').html(originalContent);
          resolve();
        });
      }
      else {
        resolve();
      }
    });
  }

  function doUploadSertifikat() {
    return new Promise((resolve, reject) => {
      let $modal = $('#form-modal');
      const $button = $('#container-file_sertifikat .upload-button');
      const files = $('#input-file_sertifikat')[0].files;
      
      if(files.length) {
        const originalContent = $button.html();
        $button.attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner mr-1"></i> Mengunggah..');
        $.ajax({
          method: 'post',
          url: `${baseURL}files/upload`,
          contentType: files[0].type,
          data: files[0],
          processData: false,
        })
        .done(function(data) {
          $modal.find('[name=file_sertifikat]').val(data.key);
        })
        .always(function() {
          $button.removeAttr('disabled').html(originalContent);
          resolve();
        });
      }
      else {
        resolve();
      }
    });
  }

  $('#container-file_cover .upload-button').on('click', doUploadCover);
  $('#container-file_sertifikat .upload-button').on('click', doUploadSertifikat);

  $('#filter-status .filter-button').on('click', function() {
    filterStatus = $(this).data('value');
    table.ajax.reload();
    $('#filter-status .filter-text').text($(this).text());
  });

  $('#filter-kategori .filter-button').on('click', function() {
    filterKategori = $(this).data('value');
    table.ajax.reload();
    $('#filter-kategori .filter-text').text($(this).text());
  });

  $('#input-check_nilai_minimum').on('input', function() {
    if(this.checked) {
      $('#input-nilai_minimum').removeAttr('disabled');
    }
    else {
      $('#input-nilai_minimum').attr('disabled', 'disabled');
    }
  })
  .trigger('input');

  $('#input-check_kehadiran_minimum').on('input', function() {
    if(this.checked) {
      $('#input-kehadiran_minimum').removeAttr('disabled');
    }
    else {
      $('#input-kehadiran_minimum').attr('disabled', 'disabled');
    }
  })
  .trigger('input');

  $('#refresh-button').on('click', function () {
    table.ajax.reload();
  });

  $('#add-button').on('click', function () {
    penanggungJawabLimit = 0;
    $('#penanggung-jawab-container').html('');
    const $modal = $('#form-modal');
    $modal.find('.modal-title').html('<i class="fa fa-plus mr-1"></i> Tambah Data');
    $modal.find('[name=id]').val('');
    $modal.find('[name=type]').val('insert');
    $modal.find('[name=nama]').val('');
    $modal.find('[name=tema]').val('');
    $modal.find('[name=id_kategori]').val('');
    $modal.find('[name=tanggal_mulai]').val('');
    $modal.find('[name=tanggal_selesai]').val('');
    $modal.find('[name=harga]').val('');
    $modal.find('[name=bersertifikat]').prop('checked', false);
    $modal.find('[name=keaktifan]').prop('checked', false);
    $('#input-check_nilai_minimum').prop('checked', false).trigger('input');
    $modal.find('[name=nilai_minimum]').val('');
    $('#input-check_kehadiran_minimum').prop('checked', false).trigger('input');
    $modal.find('[name=kehadiran_minimum]').val('');
    $('#img-cover').attr('src', '<?= base_url() ?>assets/images/placeholder-image.png');
    $modal.modal('show');
  });

  $('#edit-button').on('click', function () {
    const $modal = $('#form-modal');
    let selected = table.selectedRow();
    if(selected) {
      penanggungJawabLimit = 0;
      $('#penanggung-jawab-container').html('');
      $modal.find('.modal-title').html('<i class="fa fa-pencil-alt mr-1"></i> Edit Data');
      $modal.find('[name=id]').val(selected.id);
      $modal.find('[name=type]').val('update');
      $modal.find('[name=nama]').val(selected.nama);
      $modal.find('[name=tema]').val(selected.tema);
      $modal.find('[name=id_kategori]').val(selected.id_kategori);
      $modal.find('[name=tanggal_mulai]').val(moment(selected.tanggal_mulai).format('L'));
      $modal.find('[name=tanggal_selesai]').val(moment(selected.tanggal_selesai).format('L'));
      $modal.find('[name=harga]').val(selected.harga);
      $modal.find('[name=bersertifikat]').prop('checked', selected.bersertifikat == 1).trigger('input');
      $modal.find('[name=keaktifan]').prop('checked', selected.keaktifan == 1).trigger('input');
      if(selected.nilai_minimum == null) {
        $('#input-check_nilai_minimum').prop('checked', false).trigger('input');
        $modal.find('[name=nilai_minimum]').val('');
      }
      else {
        $('#input-check_nilai_minimum').prop('checked', true).trigger('input');
        $modal.find('[name=nilai_minimum]').val(selected.nilai_minimum);
      }

      if(selected.kehadiran_minimum == null) {
        $('#input-check_kehadiran_minimum').prop('checked', false).trigger('input');
        $modal.find('[name=kehadiran_minimum]').val('');
      }
      else {
        $('#input-check_kehadiran_minimum').prop('checked', true).trigger('input');
        $modal.find('[name=kehadiran_minimum]').val(selected.kehadiran_minimum);
      }

      if(selected.file_cover == null) {
        $('#img-cover').attr('src', '<?= base_url() ?>assets/images/placeholder-image.png');
      }
      else {
        $('#img-cover').attr('src', `<?= base_url() ?>files/open/${selected.file_cover}`);
      }

      if(selected.file_sertifikat == null) {
        $('#img-sertifikat').attr('src', '<?= base_url() ?>assets/images/placeholder-image.png');
      }
      else {
        $('#img-sertifikat').attr('src', `<?= base_url() ?>files/open/${selected.file_sertifikat}`);
      }

      $.get(`<?= base_url() ?>event/get_penanggung_jawab/${selected.id}`)
      .done(function(data) {
        if(data && data.code == 1 && Array.isArray(data.data)) {
          data.data.forEach(function(item) {
            addPenanggungJawab();
            $(`[name="penanggung_jawab[${penanggungJawabLimit - 1}][nama]"]`).val(item.nama);
            $(`[name="penanggung_jawab[${penanggungJawabLimit - 1}][jabatan]"]`).val(item.jabatan);
          });
        }
      });

      $modal.modal('show');
    }
  });

  $('#remove-button').on('click', function () {
    const $modal = $('#delete-modal');
    let selected = table.selectedRow();
    if(selected) {
      Swal.fire({
        title: 'Anda yakin akan menghapus data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya'
      })
      .then(function(result) {
        if (result.value) {
          $.ajax({
            method: 'post',
            url: `${baseURL}event`,
            data: {
              id: selected.id,
              type: 'delete',
            },
          })
          .always(function() {
            table.redrawAndKeep();
          });
        }
      });
    }
  });

  $('#agenda-button').on('click', function () {
    let selected = table.selectedRow();
    if(selected) {
      window.location.href = `${baseURL}agenda/index/${selected.id}`;
    }
  });

  $('#pendaftaran-button').on('click', function () {
    let selected = table.selectedRow();
    if(selected) {
      window.location.href = `${baseURL}pendaftaran/index/${selected.id}`;
    }
  });

  $('#peserta-button').on('click', function () {
    let selected = table.selectedRow();
    if(selected) {
      window.location.href = `${baseURL}peserta/index/${selected.id}`;
    }
  });

  $('#kehadiran-button').on('click', function () {
    let selected = table.selectedRow();
    if(selected) {
      window.location.href = `${baseURL}kehadiran/index/${selected.id}`;
    }
  });

  $('#absensi-button').on('click', function () {
    let selected = table.selectedRow();
    if(selected) {
      // window.location.href = `${baseURL}agenda/index/${selected.id}`;
    }
  });

  $('#action-form').on('submit', async function (ev) {
    ev.preventDefault();

    const $form = $(this);
    const $submitButton = $form.find('button[type=submit]');
    const originalContent = $submitButton.html();
    $submitButton.attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner mr-1"></i> Memproses..');
    await doUploadCover();
    await doUploadSertifikat();

    $.ajax({
      method: 'post',
      url: `${baseURL}event`,
      data: $form.serialize(),
    })
    .always(function() {
      $submitButton.removeAttr('disabled').html(originalContent);
      $('.form-modal').modal('hide');
      table.redrawAndKeep();
    });
  });
});
</script>