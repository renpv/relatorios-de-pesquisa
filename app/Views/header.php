<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
    <span class="fs-4">Sistema de gerenciamento de relatórios de pesquisa</span>
    </a>

    <ul class="nav nav-pills">        
        <?php if(auth()->user()): ?>
            <?php if(auth()->user()->inGroup('docente', 'discente', 'gestao')): ?>
                <li class="nav-item"><a href="/logout" class="nav-link" aria-current="page">Enviar relatório</a></li>
            <?php endif; ?>

            <div class="dropdown text-end">
                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?=base_url('images/abstract-user-flat.svg')?>" alt="mdo" width="32" height="32" class="rounded-circle">
                </a>
                <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1" style="">
                    <li><a class="dropdown-item" href="/usuario/perfil">Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/logout">Sair</a></li>
                </ul>
            </div>
        <?php endif; ?>

    </ul>
</header>