<script>
$(document).ready(function() {
  let filterStatus;
  const table = $('#datatable').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
    ajax: {
      url: `${baseURL}kehadiran/datatable/<?= $id_event ?>/<?= $tanggal ?>`,
    },
    columns: [
      {
        data: 'id',
        render: dtHelper.incrementNumber,
      },
      { data: 'npm' },
      { data: 'nama' },
      {
        data: 'jam',
        render: function(d, t, r) {
          return d == null ? '<i class="fa fa-times"></i>' : '<i class="fa fa-check"></i>';
        }
      },
      {
        data: 'jam',
        render: function(d, t, r) {
          return d == null ? '-' : moment('2020-01-01 ' + d).format('LT');
        }
      },
      {
        data: 'cara_absen',
        render: function(d, t, r) {
          return d == null ? '-' : (d == 1 ? 'Via Scan' : 'Manual');
        }
      },
      {
        data: 'ijin',
        render: function(d, t, r) {
          return d != 1 ? '<i class="fa fa-times"></i>' : '<i class="fa fa-check"></i>';
        }
      },
    ],
    dom: "<'row'<'col-sm-12 col-md-6 filter-container'><'col-sm-12 col-md-6'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
  }));

  $('#datatable_wrapper .filter-container').html(`
    <div id="filter-status" class="btn-group">
      <button type="button" class="btn btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-filter mr-1"></i>
        Tanggal: <span class="filter-text"><?= to_date_format('d/m/Y', $tanggal) ?></span>
        &nbsp;&nbsp;
      </button>
      <div class="dropdown-menu dropdown-menu-left">
      <?php foreach($tanggal_list as $item): ?>
        <a class="dropdown-item" href="<?= base_url() ?>kehadiran/index/<?= $id_event ?>/<?= $item ?>">
          <?= to_date_format('d/m/Y', $item) ?>
        </a>
      <?php endforeach; ?>
      </div>
    </div>
  `);

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

  $('#qrcode-button').on('click', function() {
    Swal.fire({
      imageUrl: `${baseURL}kehadiran/qrcode/<?= $id_event ?>`,
      imageHeight: 480,
      imageAlt: 'A tall image'
    });
  });

  $('#refresh-button').on('click', function () {
    table.ajax.reload();
  });

  $('#set-hadir-button').on('click', function () {
    const $modal = $('#form-modal');
    let selected = table.selectedRow();
    if(selected) {
      let isHadir = selected.tanggal != null;
      Swal.fire({
        title: isHadir ? 'Batalkan kehadiran?' : 'Set manual kehadiran?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya'
      })
      .then(function(result) {
        if (result.value) {
          $.ajax({
            method: 'post',
            url: `${baseURL}kehadiran/index/<?= $id_event ?>/<?= $tanggal ?>`,
            data: {
              type: 'set-hadir',
              id_mahasiswa: selected.id_mahasiswa,
              hadir: isHadir ? 0 : 1,
            },
          })
          .always(function() {
            table.redrawAndKeep();
          });
        }
      });
    }
  });

  $('#set-ijin-button').on('click', function () {
    const $modal = $('#form-modal');
    let selected = table.selectedRow();
    if(selected) {
      let isIjin = selected.ijin == 1;
      Swal.fire({
        title: isIjin ? 'Batalkan ijin?' : 'Set ijin?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya'
      })
      .then(function(result) {
        if (result.value) {
          $.ajax({
            method: 'post',
            url: `${baseURL}kehadiran/index/<?= $id_event ?>/<?= $tanggal ?>`,
            data: {
              type: 'set-ijin',
              id_mahasiswa: selected.id_mahasiswa,
              ijin: isIjin ? 0 : 1,
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