// <script>
$(document).ready(function() {
  const form = $('#form-search').on('submit', function(ev) {
    ev.preventDefault();
    let kode = $(form).find('[name=kode]').val();
    location.href = `${baseURL}sertifikat/index/${kode}`;
  })
});
// </script>