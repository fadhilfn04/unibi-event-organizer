<script>
$(document).ready(function() {

  $('#input-jam_mulai, #input-jam_selesai').datetimepicker({
    locale: 'id',
    format: 'LT',
  });

  const table = $('#datatable').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
    ajax: {
      url: `${baseURL}agenda/datatable/<?= $id_event ?>`,
    },
    columns: [
      {
        data: 'id',
        render: function (dt, i, r, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      {
        data: 'tanggal',
        render: function (d) {
          return moment(d).format('L');
        },
      },
      {
        data: 'jam_mulai',
        render: function (d) {
          return moment('2020-01-01 ' + d).format('LT');
        },
      },
      {
        data: 'jam_selesai',
        render: function (d) {
          return moment('2020-01-01 ' + d).format('LT');
        },
      },
      { data: 'kegiatan' },
      { data: 'narasumber' }
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

  $('#add-button').on('click', function () {
    const $modal = $('#form-modal');
    $modal.find('.modal-title').html('<i class="fa fa-plus mr-1"></i> Tambah Data');
    $modal.find('[name=id]').val('');
    $modal.find('[name=type]').val('insert');
    $modal.find('[name=tanggal]').val('');
    $modal.find('[name=jam_mulai]').val('');
    $modal.find('[name=jam_selesai]').val('');
    $modal.find('[name=kegiatan]').val('');
    $modal.find('[name=narasumber]').val('');
    $modal.modal('show');
  });

  $('#edit-button').on('click', function () {
    const $modal = $('#form-modal');
    let selected = table.selectedRow();
    if(selected) {
      $modal.find('.modal-title').html('<i class="fa fa-pencil-alt mr-1"></i> Edit Data');
      $modal.find('[name=id]').val(selected.id);
      $modal.find('[name=type]').val('update');
      $modal.find('[name=tanggal]').val(moment(selected.tanggal).format('YYYY-MM-DD'));
      $modal.find('[name=jam_mulai]').val(moment('2020-01-01 ' + selected.jam_mulai).format('LT'));
      $modal.find('[name=jam_selesai]').val(moment('2020-01-01 ' + selected.jam_selesai).format('LT'));
      $modal.modal('show');
      $modal.find('[name=narasumber]').val(selected.narasumber);
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
            url: `${baseURL}agenda/index/<?= $id_event ?>`,
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
      url: `${baseURL}agenda/index/<?= $id_event ?>`,
      data: $form.serialize(),
    })
    .always(function() {
      $submitButton.removeAttr('disabled').html(originalContent);
      $('.form-modal').modal('hide');
      table.redrawAndKeep();
      // $('#datatable').DataTable().ajax.reload();
    });
  });
});
</script>