<script type="text/javascript">
$(document).ready(function() {
  const form = $('#form-search').on('submit', function(ev) {
    ev.preventDefault();
    let kode = $(form).find('[name=kode]').val();
    location.href = `${baseURL}sertifikat/index/${kode}`;
  })

  const table = $('#datatable').DataTable($.extend(true, { }, dtHelper.serverSideConfig, {
    ajax: {
      url: `${baseURL}sertifikat/datatable/<?= $kode ?>`,
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
});
</script>