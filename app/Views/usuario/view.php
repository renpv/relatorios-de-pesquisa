<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<div class="col-md-6 offset-md-3">
    <h1 class="mb-4">Perfil do usuário
        <a class="btn btn-primary text-end" href="<?= base_url('usuario')?>">Listar usuários</a>
    </h1>
    <?php //dd($user);?>
    <?php if(!is_null($user)):?>
        <table class="table">
        <tbody>
            <tr>
                <td>Nome</td>
                <td><?=$user->nome?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?=$user->username?></td>
            </tr>
            <tr>
                <td>e-mail</td>
                <td><?=$user->email?></td>
            </tr>
            <tr>
                <td>Adicionar perfil</td>
                <td>
                    <form action="<?= base_url('usuario/atualizar_grupos')?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id" value="<?=$user->id; ?>">
                        <?= view_cell('FormsCheckbox', ['value' => 'superadmin', 'checked' => in_array('superadmin', $user->getGroups())])?>
                        <?= view_cell('FormsCheckbox', ['value' => 'admin', 'checked' => in_array('admin', $user->getGroups())])?>
                        <?= view_cell('FormsCheckbox', ['value' => 'clic', 'checked' => in_array('clic', $user->getGroups())])?>
                        <?= view_cell('FormsCheckbox', ['value' => 'docente', 'checked' => in_array('docente', $user->getGroups()), 'disabled' => true])?>
                        <?= view_cell('FormsCheckbox', ['value' => 'discente', 'checked' => in_array('discente', $user->getGroups()), 'disabled' => true])?>
                        <input class="btn btn-primary" type="submit" value="Atualizar perfil">
                    </form>    
                </td>
            </tr>
        </tbody>
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
