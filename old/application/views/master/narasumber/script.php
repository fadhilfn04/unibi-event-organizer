<script type="text/javascript">
$(document).ready(function() {

  $('#input-tanggal_lahir').datetimepicker({
    locale: 'id',
    format: 'YYYY-MM-DD',
  });

  // $('.select-fakultas').select2();
  // $('.select-jurusan').select2();

  const table = $('#datatable').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
    ajax: {
      url: `${baseURL}master/narasumber/datatable`,
    },
    processing: true,
    serverSide: true,
    scrollX: true,
    scrollY: '400px',
    responsive: {
        details: false
    },
    // dom: 'Bflrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    columns: [
      {
        data: 'id',
        render: dtHelper.incrementNumber,
      },
      { data: 'nama' },
      { data: 'pekerjaan' },
      { data: 'keahlian' },
      { data: 'pendidikan' },
      { data: 'email' },
      { data: 'no_telepon' },
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
    $modal.find('[name=nama]').val('');
    $modal.find('[name=pekerjaan]').val('');
    $modal.find('[name=keahlian]').val('');
    $modal.find('[name=pendidikan]').val('');
    $modal.find('[name=email]').val('');
    $modal.find('[name=no_telepon]').val('');
    $modal.modal('show');
  });

  $('#edit-button').on('click', function () {
    const $modal = $('#form-modal');
    let selected = table.selectedRow();
    if(selected) {
      $modal.find('.modal-title').html('<i class="fa fa-pencil-alt mr-1"></i> Edit Data');
      $modal.find('[name=id]').val(selected.id);
      $modal.find('[name=type]').val('update');
      $modal.find('[name=nama]').val(selected.nama);
      $modal.find('[name=pekerjaan]').val(selected.pekerjaan);
      $modal.find('[name=keahlian]').val(selected.keahlian);
      $modal.find('[name=pendidikan]').val(selected.pendidikan);
      $modal.find('[name=email]').val(selected.email);
      $modal.find('[name=no_telepon]').val(selected.no_telepon);
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
            url: `${baseURL}master/narasumber`,
            data: {
              id: selected.id,
              type: 'delete',
            },
          })
          .always(function() {
            table.redrawAndKeep();
            Swal.fire('Success', 'Data telah terhapus!', 'success');
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
      url: `${baseURL}master/narasumber`,
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