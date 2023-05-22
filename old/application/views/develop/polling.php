<p id="result"></p>
<script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
<script>
const poll = function() {
  $.get('<?= site_url() ?>notify/polling/1234abcd')
  .done(function(data) {
    console.log(data);
    $('#result').prepend(JSON.stringify(data) + '<br>');
    setTimeout(function() {
      poll();
    }, 1000);
  });
};
poll();
</script>