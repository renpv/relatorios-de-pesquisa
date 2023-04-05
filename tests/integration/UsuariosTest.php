<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\FilterTestTrait;

class UsuariosTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;
    use FilterTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    /** @test */
    public function RotaNaoDeveEstarAcessivelParaUsuarioNaoLogado()
    {
        $filter = $this->getFiltersForRoute('/usuario', 'before');
        $this->assertEquals(['session'], $filter);
        $this->assertFilter('/usuario', 'before', 'session');

        $result = $this->call('get', '/usuario');
        $result->assertRedirect();
    }

    /** @test */
    public function DeveRedirecionarParaDashboardAoLogarComUsuarioNaoSuperadmin()
    {
        // Definindo dados do usuário
        $dataLogin = ['username'  => 'docente', 'password' => 'valido'];

        // Fazendo a requisição
        $result = $this->call('post', 'login', $dataLogin);

        // Fazendo a requisição
        $result = $this->call('get', 'usuario');

        // Testando se a requisição foi realizada
        $result->assertOK();

        // Verificando se houve redirecionamento para a URL base do site (rota protegida)
        $url = $result->getRedirectUrl();
        $this->assertEquals(site_url('/'), $url);

        auth()->logout();
    }

    /** @test */
    public function DevePermitirAcessarAsRotasProtegidasDeUsuarioAoLogarComUsuarioSuperadmin()
    {
        // Definindo dados do usuário
        $dataLogin = ['username'  => 'docente', 'password' => 'valido'];

        // Fazendo a requisição
        $result = $this->call('post', 'login', $dataLogin);

        auth()->user()->addGroup('superadmin');

        // Fazendo a requisição
        $result = $this->call('get', 'usuario');

        // Testando se a requisição foi realizada
        $result->assertOK();
        $result->assertNotRedirect();

        // Fazendo a requisição de visualização de um usuário
        $result = $this->call('get', 'usuario/' . auth()->id());

        // Testando se a requisição foi realizada
        $result->assertOK();
        $result->assertNotRedirect();

        auth()->logout();
    }

    /** @test */
    public function DevePermitirAtualizarGruposDoUsuarioAoLogarComUsuarioSuperadmin()
    {
        // Definindo dados do usuário
        $dataLogin = ['username'  => 'docente', 'password' => 'valido'];

        // Fazendo a requisição
        $result = $this->call('post', 'login', $dataLogin);

        $idUsuario = auth()->id();

        auth()->user()->addGroup('superadmin');
        auth()->user()->addGroup('admin');

        $params = [
            'id'         => $idUsuario,
            'superadmin' => 'superadmin',
            'clic'       => 'clic',
        ];

        // Fazendo a requisição e incluindo todos os grupos
        $result = $this->call('post', 'usuario/atualizar_grupos', $params);

        // Testando se a requisição foi realizada
        $result->assertOK();

        // Fazendo logout e login do usuário para aplicar as alterações
        auth()->logout();
        auth()->loginById($idUsuario);

        // Fazendo asserts das nas alterações realizadas
        $this->assertTrue(auth()->user()->inGroup('superadmin'));
        $this->assertTrue(auth()->user()->inGroup('clic'));
        $this->assertFalse(auth()->user()->inGroup('admin'));

        auth()->logout();
    }

    /** @test */
    public function DeveNegarAcessoATodasAsRotaUsuarioProtegidasParaSuperadmin()
    {
        // Definindo dados do usuário
        $dataLogin = ['username'  => 'docente', 'password' => 'valido'];

        // Fazendo a requisição
        $result = $this->call('post', 'login', $dataLogin);

        // Fazendo a requisição de listagem de usuários
        $result = $this->call('get', 'usuario/listar');

        // Testando se a requisição foi realizada
        $result->assertOK();

        // Verificando se houve redirecionamento para a URL base do site (rota protegida)
        $url = $result->getRedirectUrl();
        $this->assertEquals(site_url('/'), $url);

        // Fazendo a requisição de visualização de um usuário
        $result = $this->call('get', 'usuario/' . auth()->id());

        // Testando se a requisição foi realizada
        $result->assertOK();

        // Verificando se houve redirecionamento para a URL base do site (rota protegida)
        $url = $result->getRedirectUrl();
        $this->assertEquals(site_url('/'), $url);

        // Fazendo a requisição de atualização de um usuário
        $result = $this->call('put', 'usuario/atualizar_grupos');

        // Testando se a requisição foi realizada
        $result->assertOK();

        // Verificando se houve redirecionamento para a URL base do site (rota protegida)
        $url = $result->getRedirectUrl();
        $this->assertEquals(site_url('/'), $url);

        auth()->logout();
    }
}
