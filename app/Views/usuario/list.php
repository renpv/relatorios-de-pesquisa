<?= $this->extend('default') ?>

<?= $this->section('css') ?>
<link href="<?= base_url()?>/js/DataTables/datatables.min.css" rel="stylesheet"/>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-md-10 offset-md-1">
    <h1>Usuários</h1>
    <?php if(!is_null($users)):?>
        <table id="usuarios"  class="table table-striped"  style="width:100%">
            <thead>
                <tr>
                    <th>Nome de usuário</th>
                    <th>Usuário</th>
                    <th>Funções</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user):?>
                <tr>
                    <td><?= $user->username ?></td>
                    <td><?= $user->nome ?></td>
                    <td>
                        <a href="<?= base_url('usuario/view/') . $user->id?>"><i data-feather="eye"></i></a>
                        <a href="<?= base_url('usuario/edit/') . $user->id?>"><i data-feather="edit"></i></a>
                    </td>
                </tr> 
            <?php endforeach; ?>
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

<?= $this->section('javascript') ?>
    <script src="<?= base_url()?>/js/jquery-3.6.4.min.js"></script>
    <script src="<?= base_url()?>/js/DataTables/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#usuarios').DataTable({
                language: {
                    url: '<?= base_url()?>/js/DataTables/i18n/pt-BR.json',
                },
            });
        });
    </script>
<?= $this->endSection() ?>