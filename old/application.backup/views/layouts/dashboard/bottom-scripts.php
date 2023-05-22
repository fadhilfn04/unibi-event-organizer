<!-- jQuery -->
<script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="<?= base_url('assets') ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- Select2 -->
<script src="<?= base_url('assets') ?>/plugins/select2/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="<?= base_url('assets') ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/moment/locale/id.js"></script>
<script src="<?= base_url('assets') ?>/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url('assets') ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="<?= base_url('assets') ?>/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- SweetAlert2 -->
<script src="<?= base_url('assets') ?>/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- DataTables -->
<script src="<?= base_url('assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-select/js/dataTables.select.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-select/js/select.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>

<script>
$(function() {
  moment.locale('id');
  $('#logout-button').click(function(ev) {
    ev.preventDefault();
    $('#logout-modal').modal('show');
  });
  
  window.dtHelper = { };
  window.dtHelper.dataTransform = function(data) {
    const order = data.order.reduce(function(acc, item) {
      const columnName = data.columns[item.column].data;
      acc[columnName] = item.dir;
      return acc;
    }, { });

    return {
      draw: data.draw,
      start: data.start,
      length: data.length,
      term: data.search.value,
      order: order,
    };
  }
  window.dtHelper.serverSideConfig = {
    serverSide: true,
    processing: true,
    responsive: true,
    select: 'single',
    aaSorting: [ ],
    ajax: {
      data: dtHelper.dataTransform,
    },
    columnDefs: [
      {orderable: false, searchable: false, targets: [0]}
    ],
  };
  window.dtHelper.incrementNumber = function (dt, i, r, meta) {
    return meta.row + meta.settings._iDisplayStart + 1;
  };

  $.fn.dataTable.Api.register('redrawAndKeep()', function(options) {
    const table = this;
    const currentPage = table.page();
    const currentRow = table.rows({ selected: true });
    table.page(currentPage).draw('page');
    const fn = function() {
      table.row(currentRow).select();
      table.off('draw', fn);
    }
    table.on('draw', fn);
  });
  $.fn.dataTable.Api.register('selectedRow()', function(options) {
    return this.rows({ selected: true }).data()[0];
  });
  $.fn.dataTable.Api.register('selectedRows()', function(options) {
    return this.rows({ selected: true }).data();
  });

  $.fn.datetimepicker.Constructor.Default = $.extend({ }, $.fn.datetimepicker.Constructor.Default, {
    icons: {
        time: 'fas fa-clock',
        date: 'fas fa-calendar',
        up: 'fas fa-arrow-up',
        down: 'fas fa-arrow-down',
        previous: 'fas fa-chevron-left',
        next: 'fas fa-chevron-right',
        today: 'fas fa-calendar-check-o',
        clear: 'fas fa-trash',
        close: 'fas fa-times'
    }
  });

  window.select2Helper = { };
  window.select2Helper.appendIfEmpty = function(selector, data) {
    const selectDuo = $(selector);
    if(selectDuo.find(`option[value=${data.id}]`).length) {
      selectDuo.val(data.id).trigger('change');
    } else { 
      let newOption = new Option(data.text, data.id, false, false);
      selectDuo.append(newOption).trigger('change');
    }
  };
});
</script>

<?php if(!empty($bottom_scripts)): ?>
<?php foreach(((array) $bottom_scripts) as $script): ?>
<?php $this->load->view($script); ?>
<?php endforeach; ?>
<?php endif; ?>