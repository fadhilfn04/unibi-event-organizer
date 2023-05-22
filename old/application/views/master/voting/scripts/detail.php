<script>
$(document).ready(function() {
  let tableKandidat, tablePemilih;
  const initializeTableOnShown = function(e) {
    if(e.target.id == 'detail-pemilih-tab' && tablePemilih == null) {
      tablePemilih = $('#table-pemilih').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
        dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        select: true,
        ajax: {
          url: `${baseURL}master/voting/detail/${dataVoting.id}/pemilih-datatable`,
        },
        columns: [
          {
            data: 'id',
            render: dtHelper.incrementNumber,
          },
          { data: 'npm' },
          { data: 'nama' },
          { data: 'tahun_masuk' },
          { data: 'jurusan' },
          {
            data: 'id_user',
            render: function(d, t, r) {
              return d ? 'Terdaftar' : 'Belum Terdaftar';
            }
          },
        ],
      }));

      tablePemilih.on('select', function () {
        $('.row-action-button[data-table=table-pemilih]').removeAttr('disabled');
      });

      tablePemilih.on('deselect', function () {
        $('.row-action-button[data-table=table-pemilih]').attr('disabled', 'disabled');
      });

      tablePemilih.on('draw', function () {
        let selected = tablePemilih.selectedRow();
        if(selected) {
          $('.row-action-button[data-table=table-pemilih]').removeAttr('disabled');
        }
        else {
          $('.row-action-button[data-table=table-pemilih]').attr('disabled', 'disabled');
        }
      });

      $('.refresh-button[data-table=table-pemilih]').on('click', function () {
        tablePemilih.ajax.reload();
      });

      const $modal = $('#form-pemilih-modal');
      let $tahunMasukSelect = $modal.find('[name="tahun_masuk[]"]').select2();
      let $fakultasSelect = $modal.find('[name="id_fakultas[]"]').select2();
      let $jurusanSelect = $modal.find('[name="id_jurusan[]"]').select2();
      let $mahasiswaSelect = $modal.find('[name="id_mahasiswa[]"]').select2({
        dropdownParent: $modal,
        ajax: {
          url: `${baseURL}master/mahasiswa/select2`,
          dataType: 'json',
        },
        templateResult: function(data) {
          if(!data.id) {
            return data.text;
          }
          return $(`<small class="text-muted">${data.npm}</small> <b>${data.nama}</b><br><span>${data.fakultas} | ${data.jurusan}</span>`);
        },
      });

      $('.add-button[data-table=table-pemilih]').on('click', function () {
        $modal.find('.modal-title').html('<i class="fas fa-plus mr-1"></i> Tambah Data');
        $modal.find('[name=type]').val('insert_batch');
        $tahunMasukSelect.val(null).trigger('change');
        $fakultasSelect.val(null).trigger('change');
        $jurusanSelect.val(null).trigger('change');
        $mahasiswaSelect.val(null).trigger('change');
        $modal.modal('show');
      });

      $('.remove-batch-button[data-table=table-pemilih]').on('click', function () {
        $modal.find('.modal-title').html('<i class="fas fa-plus mr-1"></i> Tambah Data');
        $modal.find('[name=type]').val('delete_batch');
        $tahunMasukSelect.val(null).trigger('change');
        $fakultasSelect.val(null).trigger('change');
        $jurusanSelect.val(null).trigger('change');
        $mahasiswaSelect.val(null).trigger('change');
        $modal.modal('show');
      });

      $('.remove-button[data-table=table-pemilih]').on('click', function () {
        let selected = tablePemilih.selectedRows();
        if(selected.length) {
          selected = Array.from(selected.pluck('id_mahasiswa'));
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
                url: `${baseURL}master/voting/detail/${dataVoting.id}/pemilih`,
                data: {
                  id_mahasiswa: selected,
                  type: 'delete',
                },
              })
              .always(function() {
                tablePemilih.redrawAndKeep();
                $('#refresh-pemilih-count').trigger('click');
              });
            }
          });
        }
      });

      $('#pemilih-action-form').on('submit', function(ev) {
        ev.preventDefault();

        const $form = $(this);
        const $submitButton = $form.find('button[type=submit]');
        const originalContent = $submitButton.html();
        $submitButton.attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner mr-1"></i> Memproses..');

        $.ajax({
          method: 'post',
          url: `${baseURL}master/voting/detail/${dataVoting.id}/pemilih`,
          data: $form.serialize(),
        })
        .done(function() {
          $('#refresh-pemilih-count').trigger('click');
        })
        .always(function() {
          $submitButton.removeAttr('disabled').html(originalContent);
          $('.form-modal').modal('hide');
          tablePemilih.redrawAndKeep();
        });
      });
    }
    else if(e.target.id == 'detail-kandidat-tab' && tableKandidat == null) {
      tableKandidat = $('#table-kandidat').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
        dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        ajax: {
          url: `${baseURL}master/voting/detail/${dataVoting.id}/kandidat-datatable`,
        },
        columns: [
          {
            data: 'id',
            render: dtHelper.incrementNumber,
          },
          { data: 'npm' },
          { data: 'nama' },
          { data: 'tahun_masuk' },
          { data: 'jurusan' },
        ],
      }));

      tableKandidat.on('select', function () {
        $('.row-action-button[data-table=table-kandidat]').removeAttr('disabled');
      });

      tableKandidat.on('deselect', function () {
        $('.row-action-button[data-table=table-kandidat]').attr('disabled', 'disabled');
      });

      tableKandidat.on('draw', function () {
        let selected = tableKandidat.selectedRow();
        if(selected) {
          $('.row-action-button[data-table=table-kandidat]').removeAttr('disabled');
        }
        else {
          $('.row-action-button[data-table=table-kandidat]').attr('disabled', 'disabled');
        }
      });

      $('.refresh-button[data-table=table-kandidat]').on('click', function () {
        tableKandidat.ajax.reload();
      });

      const $modal = $('#form-kandidat-modal');

      $('.add-button[data-table=table-kandidat]').on('click', function () {
        $modal.find('.modal-title').html('<i class="fas fa-plus mr-1"></i> Tambah Data');
        $modal.find('[name=id]').val('');
        $modal.find('[name=type]').val('insert');
        $modal.find('[name=id_mahasiswa]').val(null).trigger('change');
        $modal.find('[name=foto]').val('');
        $modal.find('[name=visi]').val('');
        $modal.find('[name=misi]').val('');
        $modal.find('[name=npm]').val('');
        $modal.find('[name=jurusan]').val('');
        $modal.find('[name=fakultas]').val('');
        $('#img-foto').attr('src', `${baseURL}assets/images/placeholder-image.png`);
        $modal.modal('show');
      });

      $('.edit-button[data-table=table-kandidat]').on('click', function () {
        let selected = tableKandidat.selectedRow();
        if(selected) {
          $modal.find('.modal-title').html('<i class="fa fa-pencil-alt mr-1"></i> Edit Data');
          $modal.find('[name=id]').val(selected.id);
          $modal.find('[name=type]').val('update');
          select2Helper.appendIfEmpty($modal.find('[name=id_mahasiswa]'), {
            id: selected.id_mahasiswa, text: selected.nama
          });
          $modal.find('[name=foto]').val('');
          $modal.find('[name=visi]').val(selected.visi);
          $modal.find('[name=misi]').val(selected.misi);
          $modal.find('[name=npm]').val(selected.npm);
          $modal.find('[name=jurusan]').val(selected.jurusan);
          $modal.find('[name=fakultas]').val(selected.fakultas);
          if(selected.foto) {
            $('#img-foto').attr('src', `${baseURL}files/open/${selected.foto}`);
          }
          else {
            $('#img-foto').attr('src', `${baseURL}assets/images/placeholder-image.png`);
          }
          $modal.modal('show');
        }
      });

      const $modalDetail = $('#form-kandidat-modal-detail');

      $('.detail-button[data-table=table-kandidat]').on('click', function () {
        let selected = tableKandidat.selectedRow();
        if(selected) {
          $modalDetail.find('[name=nama]').val(selected.nama);
          $modalDetail.find('[name=visi]').val(selected.visi);
          $modalDetail.find('[name=misi]').val(selected.misi);
          $modalDetail.find('[name=npm]').val(selected.npm);
          $modalDetail.find('[name=jurusan]').val(selected.jurusan);
          $modalDetail.find('[name=fakultas]').val(selected.fakultas);
          if(selected.foto) {
            $('#img-foto-detail').attr('src', `${baseURL}files/open/${selected.foto}`);
          }
          else {
            $('#img-foto-detail').attr('src', `${baseURL}assets/images/placeholder-image.png`);
          }
          $modalDetail.modal('show');
        }
      });

      $('.remove-button[data-table=table-kandidat]').on('click', function () {
        let selected = tableKandidat.selectedRow();
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
                url: `${baseURL}master/voting/detail/${dataVoting.id}/kandidat`,
                data: {
                  id: selected.id,
                  type: 'delete',
                },
              })
              .always(function() {
                tableKandidat.redrawAndKeep();
                $('#refresh-kandidat-count').trigger('click');
              });
            }
          });
        }
      });
  
      let blobURL;

      $('#form-kandidat-modal').on('hidden.bs.modal', function(ev) {
        if(blobURL) {
          URL.revokeObjectURL(blobURL);
          blobURL = null;
          $('#img-foto').attr('src', `${baseURL}assets/images/placeholder-image.png`);
        }
      });

      $('#input-foto').on('change', function(ev) {
        if(this.files.length) {
          if(blobURL) {
            URL.revokeObjectURL(blobURL);
            blobURL = null;
          }
          blobURL = URL.createObjectURL(this.files[0]);
          $(this).next('.custom-file-label').text(this.files[0].name)
          $('#img-foto').attr('src', blobURL);
        }
      });

      $modal.find('[name=id_mahasiswa]').select2({
        dropdownParent: $modal,
        ajax: {
          url: `${baseURL}master/voting/detail/${dataVoting.id}/kandidat-select2`,
          dataType: 'json',
        },
        templateResult: function(data) {
          if(!data.id) {
            return data.text;
          }
          return $(`<small class="text-muted">${data.npm}</small> <b>${data.nama}</b><br><span>${data.fakultas} | ${data.jurusan}</span>`);
        },
      })
      .on('select2:select', function(ev) {
        const data = ev.params.data;
        $modal.find('[name=npm]').val(data.npm);
        $modal.find('[name=jurusan]').val(data.jurusan);
        $modal.find('[name=fakultas]').val(data.fakultas);
      });

      $('#kandidat-action-form').on('submit', function(ev) {
        ev.preventDefault();

        const $form = $(this);
        const $submitButton = $form.find('button[type=submit]');
        const originalContent = $submitButton.html();
        $submitButton.attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner mr-1"></i> Memproses..');

        $.ajax({
          method: 'post',
          url: `${baseURL}master/voting/detail/${dataVoting.id}/kandidat`,
          data: $form.serialize(),
        })
        .done(function() {
          $('#refresh-kandidat-count').trigger('click');
        })
        .always(function() {
          $submitButton.removeAttr('disabled').html(originalContent);
          $('.form-modal').modal('hide');
          tableKandidat.redrawAndKeep();
        });
      });

      $('#upload-button').on('click', function () {
        const $button = $(this);
        const files = $('#input-foto')[0].files;
        if(files.length) {
          const originalContent = $button.html();
          $button.attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner mr-1"></i> Mengunggah..');
          $.ajax({
            method: 'post',
            url: `${baseURL}files/upload`,
            data: files[0],
            processData: false,
          })
          .done(function(data) {
            $modal.find('[name=foto]').val(data.key);
          })
          .always(function() {
            $button.removeAttr('disabled').html(originalContent);
          });
        }
      });
    }
    else {
      $('[data-toggle=pill]').off('shown.bs.tab', initializeTableOnShown);
    }
  }

  $('[data-toggle=pill]').on('shown.bs.tab', initializeTableOnShown);
  $('[data-toggle=pill].active').trigger('shown.bs.tab');

  $('#refresh-suara-count').on('click', function(ev) {
    ev.preventDefault();
    $('#suara-count-overlay').show();
    $.ajax({
      method: 'get',
      url: `${baseURL}master/voting/detail/${dataVoting.id}/suara-count`,
    })
    .done(function(data) {
      $('#suara-count').text(data.count);
    })
    .always(function() {
      $('#suara-count-overlay').hide();
    });
  });

  $('#refresh-kandidat-count').on('click', function(ev) {
    ev.preventDefault();
    $('#kandidat-count-overlay').show();
    $.ajax({
      method: 'get',
      url: `${baseURL}master/voting/detail/${dataVoting.id}/kandidat-count`,
    })
    .done(function(data) {
      $('#kandidat-count').text(data.count);
    })
    .always(function() {
      $('#kandidat-count-overlay').hide();
    });
  });

  $('#refresh-pemilih-count').on('click', function(ev) {
    ev.preventDefault();
    $('#pemilih-count-overlay').show();
    $.ajax({
      method: 'get',
      url: `${baseURL}master/voting/detail/${dataVoting.id}/pemilih-count`,
    })
    .done(function(data) {
      $('#pemilih-count').text(data.count);
    })
    .always(function() {
      $('#pemilih-count-overlay').hide();
    });
  });
});
</script>