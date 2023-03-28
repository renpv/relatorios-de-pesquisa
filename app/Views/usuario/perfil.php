<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<div class="col-md-6 offset-md-3">
    <h1>Perfil do usuário</h1>
    <?php if(!is_null($profile)):?>
        <table>
            <?php foreach($profile as $k => $v):?>
                <br><?= ucfirst(str_replace('_', ' ', $k)) . ': ' . $v ?>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Não foi possível encontrar as informações do seu perfil.</p>
        <p>Por favor, informe as credenciais de acesso do Sigaa para atualizar seu cadastro.</p>
        <form method="post" action="<?= base_url('usuario/atualizar_perfil'); ?>" >
        <?= csrf_field() ?>
            <div class="mb-3">
                <label for="username" class="form-label">Usuário (Mesmo do Sigaa)</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha (Mesma do Sigaa)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <input class="btn btn-primary" type="submit" value="Atualizar Perfil">
        </form>
    <?php endif; ?>
    </div>
<?= $this->endSection() ?>
