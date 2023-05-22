<p id="result"></p>
<script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
<script>
$(document).ready(function() {
  const eventSource = new EventSource('<?= site_url('sse/listen/' . $sse_id) ?>');
  eventSource.addEventListener('open', function() {
    console.log('Connection Open');
    eventSource.addEventListener('<?= $sse_id ?>', function(ev) {
      $('#result').prepend(ev.data + '<br>');
      const data = JSON.parse(ev.data);
      if(data.count == data.sent) {
        console.log('Closing connection');
        eventSource.close();
      }
    });
  });
  eventSource.addEventListener('error', function() {
    console.log('Connection error');
    console.log('Closing connection');
    eventSource.close();
  });
});
</script>