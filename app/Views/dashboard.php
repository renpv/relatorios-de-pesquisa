<?= $this->extend('default') ?>

<?= $this->section('content') ?>

<h1 class="mb-5">Dashboard</h1>

<div class="d-flex flex-wrap">
    <div class="col-md-6 col-lg-4 px-2">
        <?php if(auth()->user()->inGroup('docente', 'discente', 'gestao')): ?>
            <?= view_cell('ComponentCard', [
                'title'       => 'Enviar relatório',
                'subtitle'    => '',
                'description' => 'Enviar relatório parcial ou final para homologação e avaliação da CLIC',
                'base_url'    => 'relatorio/enviar']); ?>
            <?php endif; ?>
            <?php if(auth()->user()->inGroup('superadmin')): ?>
                <?= view_cell('ComponentCard', [
                    'title'       => 'Usuários',
                    'subtitle'    => '',
                    'description' => 'Listar, editar, autorizar usuários no sistema',
                    'base_url'    => 'usuario/listar']); ?>
            <?php endif; ?>
        </div>
    </div>
    
<?= $this->endSection() ?>
