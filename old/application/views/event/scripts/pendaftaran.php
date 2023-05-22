<script>
$(document).ready(function() {
  let filterStatus;
  const table = $('#datatable').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
    ajax: {
      url: `${baseURL}pendaftaran/datatable/<?= $id_event ?>`,
    },
    columns: [
      {
        data: 'id',
        render: dtHelper.incrementNumber,
      },
      { data: 'npm' },
      { data: 'nama' },
      { data: 'jurusan' },
      {
        data: null,
        render: function(d, t, r) {
          if(r.file_bukti) {
            return (`<a target="_blank" href="<?= base_url() ?>files/open/${r.file_bukti}" class="table-cell font-italic text-info">(lihat foto)</a>`);
          }
          else {
            return (`<a href="javascript:;" class="table-cell font-italic text-danger">(belum upload)</a>`);
          }
        }
      },
      {
        data: 'tanggal_bukti',
        render: function(d, t, r) {
          if(d) {
            return moment(d).format('DD/MM/YY hh:mm:ss');
          }
          else {
            return '-';
          }
        }
      },
      {
        data: 'terverifikasi',
        render: function(d, t, r) {
          if(r.terverifikasi != 0) {
            return (`<span class="table-cell text-success">Terverifikasi</span>`);
          }
          else {
            return (`<span class="table-cell text-danger">Belum Terverifikasi</span>`);
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
        <button type="button" class="dropdown-item filter-button" data-value="sudah-verifikasi">
          Sudah verifikasi
        </button>
        <button type="button" class="dropdown-item filter-button" data-value="belum-verifikasi">
          Belum verifikasi
        </button>
      </div>
    </div>
  `);

  $('#filter-status .filter-button').on('click', function() {
    filterStatus = $(this).data('value');
    table.ajax.reload();
    $('#filter-status .filter-text').text($(this).text());
  });

  $('#refresh-button').on('click', function () {
    table.ajax.reload();
  });

  $('#verifikasi-button').on('click', function () {
    let selected = table.selectedRow();
    if(selected) {
      Swal.fire({
        title: 'Anda yakin akan memverifikasi peserta ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya'
      })
      .then(function(result) {
        if (result.value) {
          $.ajax({
            method: 'post',
            url: `${baseURL}pendaftaran/index/<?= $id_event ?>`,
            data: {
              id: selected.id,
              type: 'verification',
            },
          })
          .always(function() {
            table.redrawAndKeep();
          });
        }
      });
    }
  });

  $('#remove-button').on('click', function () {
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
            url: `${baseURL}pendaftaran/index/<?= $id_event ?>`,
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
});
</script>