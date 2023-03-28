<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<div class="container">
<div class="row">
    <div class="col-md-6 offset-md-3">
        <h1 class="my-5">Login</h1>
        
        <form method="post" action="<?= base_url('login'); ?>" >
        <?= csrf_field() ?>
            <div class="mb-3">
                <label for="username" class="form-label">Usuário (Mesmo do Sigaa)</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha (Mesma do Sigaa)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <input class="btn btn-primary" type="submit" value="Fazer Login">
        </form>
    </div>
  </div>

</div>
    
<?= $this->endSection() ?>