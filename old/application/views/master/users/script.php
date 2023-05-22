<script>
$(document).ready(function() {

  const table = $('#datatable').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
    ajax: {
      url: `${baseURL}master/users/datatable`,
    },
    columns: [
      {
        data: 'id',
        render: function (dt, i, r, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      { data: 'username' }
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
    $modal.find('[name=username]').val('');
    $modal.find('[name=role]').val('');
    $modal.find('[name=password]').val('');
    $modal.modal('show');
  });

  $('#edit-button').on('click', function () {
    const $modal = $('#form-modal');
    let selected = table.selectedRow();
    if(selected) {
      $modal.find('.modal-title').html('<i class="fa fa-pencil-alt mr-1"></i> Edit Data');
      $modal.find('[name=id]').val(selected.id);
      $modal.find('[name=type]').val('update');
      $modal.find('[name=username]').val(selected.username);
      $modal.find('[name=role]').val(selected.role);
      $modal.find('[name=password_hash]').val(selected.password_hash);
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
            url: `${baseURL}master/users`,
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
      url: `${baseURL}master/users`,
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