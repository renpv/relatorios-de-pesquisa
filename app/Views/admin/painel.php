<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<div class="container">
<div class="row">
    <div class="col-md-6 offset-md-3">
        <h1 class="my-5">Painel administrativo</h1>
        <?= $this->include('alerts') ?>
    </div>
  </div>

</div>
    
<?= $this->endSection() ?>