<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="ie=edge">

<title><?= $title ?> | <?= APP_NAME ?> </title>

<link rel="icon" href="<?= base_url('assets') ?>/images/logo.png" sizes="32x32" />

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

<?php if(!empty($top_scripts)): ?>
<?php foreach(((array) $top_scripts) as $script): ?>
<?php $this->load->view($script); ?>
<?php endforeach; ?>
<?php endif; ?>