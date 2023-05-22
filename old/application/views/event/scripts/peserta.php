<script>
window.dataEvent = <?= json_encode($data_event) ?>;
</script>
<script>
$(document).ready(function () {
  const table = $('#datatable').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
    ajax: {
      url: `${baseURL}peserta/datatable/<?= $id_event ?>`,
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
          if(r.jumlah_kehadiran > dataEvent.total_hari) {
            return '100%';
          }
          else {
            return `${(r.jumlah_kehadiran / dataEvent.total_hari * 100).toFixed(2)}%`;
          }
        }
      },
      {
        data: 'nilai',
        render: function(d, t, r) {
          return d == null ? '-' : Number(d).toLocaleString('id-ID');
        }
      },
      {
        data: 'keaktifan',
        render: function(d, t, r) {
          return d != 1 ? '<i class="fa fa-times"></i>' : '<i class="fa fa-check"></i>';
        }
      },
      {
        data: null,
        render: function(d, t, r) {
          
          return r.dapat_sertifikat == 1 ? 'Ya' : 'Tidak';
          // return dapatSertifikat(dataEvent, r) ? 'Ya' : 'Tidak';
        }
      },
      { data: 'kode_sertifikat',
        render: function ( data, type, row ) {
          return row.dapat_sertifikat == 1 ? '<button type="button" class="btn btn-primary row-action-button" id="sertifikat-button">' + data + '</button>' : '<button type="button" class="btn btn-primary disabled" id="btn-sertifikat">' + data + '</button>';
        }
      },
    ],
  }));
  
  $('#datatable tbody').on('click', 'button', function () {
    const $modal = $('#form-modal');
    let selected = table.selectedRow();
    if(selected && dapatSertifikat(dataEvent, selected)) {
      window.open(`<?= base_url() ?>files/certificate/<?= $id_event ?>/${selected.id_mahasiswa}`, '_blank');
      // window.location.href = ;
    }
  });

  $('#btn-sertifikat').on('click', function () {
    table.ajax.reload();
  });

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

  function dapatSertifikat(event, peserta) {
    let dapatSertifikat = event.bersertifikat == 1;
    if(event.nilai_minimum != null) {
      dapatSertifikat = dapatSertifikat && Number(peserta.nilai) >= Number(event.nilai_minimum);
    }
    if(event.kehadiran_minimum != null) {
      let persentaseKahadiran = (peserta.jumlah_kehadiran / event.total_hari * 100);
      dapatSertifikat = dapatSertifikat && Number(persentaseKahadiran) >= Number(event.kehadiran_minimum);
    }
    if(event.keaktifan != null) {
      dapatSertifikat = dapatSertifikat && peserta.keaktifan == 1;
    }
    
    return dapatSertifikat;
  };

  $('#refresh-button').on('click', function () {
    table.ajax.reload();
  });

  $('#set-nilai-button').on('click', function () {
    const $modal = $('#form-modal');
    let selected = table.selectedRow();
    if(selected) {
      $modal.find('.modal-title').html('<i class="fa fa-pencil-alt mr-1"></i> Edit Data');
      $modal.find('[name=id]').val(selected.id);
      $modal.find('[name=type]').val('set-nilai');
      $modal.find('[name=nilai]').val(selected.nilai);
      $modal.modal('show');
    }
  });

  $('#set-keaktifan-button').on('click', function() {
    let selected = table.selectedRow();
    if(selected) {
      let isAktif = selected.keaktifan == 1;
      Swal.fire({
        title: isAktif ? 'Set keaktifan menjadi tidak aktif?' : 'Set keaktifan menjadi aktif?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya'
      })
      .then(function(result) {
        if (result.value) {
          $.ajax({
            method: 'post',
            url: `${baseURL}peserta/index/<?= $id_event ?>`,
            data: {
              type: 'set-keaktifan',
              id: selected.id,
              keaktifan: isAktif ? 0 : 1,
            },
          })
          .always(function() {
            table.redrawAndKeep();
          });
        }
      });
    }
  });

  $('#sertifikat-button').on('click', function () {
    const $modal = $('#form-modal');
    let selected = table.selectedRow();
    if(selected && dapatSertifikat(dataEvent, selected)) {
      window.open(`<?= base_url() ?>files/certificate/<?= $id_event ?>/${selected.id_mahasiswa}`, '_blank');
      // window.location.href = ;
    }
  });

  $('#action-form').on('submit', function (ev) {
    ev.preventDefault();

    const $form = $(this);
    const $submitButton = $form.find('button[type=submit]');
    const originalContent = $submitButton.html();
    $submitButton.attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner mr-1"></i> Memproses..');

    $.ajax({
      method: 'post',
      url: `${baseURL}peserta/index/<?= $id_event ?>`,
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