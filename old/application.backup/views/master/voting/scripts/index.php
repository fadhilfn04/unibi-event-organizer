<script>
$(document).ready(function() {

  $('#input-tanggal_mulai, #input-tanggal_selesai').datetimepicker({
    locale: 'id',
    format: 'YYYY-MM-DD HH:mm:ss',
  });

  const table = $('#datatable').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
    ajax: {
      url: `${baseURL}master/voting/datatable`,
    },
    columns: [
      {
        data: 'id',
        render: dtHelper.incrementNumber,
      },
      { data: 'kode' },
      { data: 'nama' },
      {
        data: 'tanggal_mulai', render: function(d) {
          return moment(d).format('LLLL');
        }
      },
      {
        data: 'tanggal_selesai', render: function(d) {
          return moment(d).format('LLLL');
        }
      },
      { data: 'keterangan' },
    ],
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

  $('#refresh-button').on('click', function () {
    table.ajax.reload();
  });

  $('#detail-button').on('click', function () {
    let selected = table.selectedRow();
    if(selected) {
      window.location.href = `${baseURL}master/voting/detail/${selected.id}`;
    }
  });

  $('#add-button').on('click', function () {
    const $modal = $('#form-modal');
    $modal.find('.modal-title').html('<i class="fa fa-plus mr-1"></i> Tambah Data');
    $modal.find('[name=id]').val('');
    $modal.find('[name=type]').val('insert');
    $modal.find('[name=kode]').val('');
    $modal.find('[name=nama]').val('');
    $modal.find('[name=tanggal_mulai]').val('');
    $modal.find('[name=tanggal_selesai]').val('');
    $modal.find('[name=keterangan]').val('');
    $modal.modal('show');
  });

  $('#edit-button').on('click', function () {
    const $modal = $('#form-modal');
    let selected = table.selectedRow();
    if(selected) {
      $modal.find('.modal-title').html('<i class="fa fa-pencil-alt mr-1"></i> Edit Data');
      $modal.find('[name=id]').val(selected.id);
      $modal.find('[name=type]').val('update');
      $modal.find('[name=kode]').val(selected.kode);
      $modal.find('[name=nama]').val(selected.nama);
      $modal.find('[name=tanggal_mulai]').val(selected.tanggal_mulai);
      $modal.find('[name=tanggal_selesai]').val(selected.tanggal_selesai);
      $modal.find('[name=keterangan]').val(selected.keterangan);
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
            url: `${baseURL}master/voting`,
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

  $('#action-form').on('submit', function(ev) {
    ev.preventDefault();

    const $form = $(this);
    const $submitButton = $form.find('button[type=submit]');
    const originalContent = $submitButton.html();
    $submitButton.attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner mr-1"></i> Memproses..');

    $.ajax({
      method: 'post',
      url: `${baseURL}master/voting`,
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