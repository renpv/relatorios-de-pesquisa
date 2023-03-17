<?php 
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\FilterTestTrait;

class LoginTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;
    use FilterTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    public function testRootRouteRedirectsToLoginWhenNotLoggedIn()
    {
        $filter = $this->getFiltersForRoute('/', 'before');
        $this->assertEquals(['session'], $filter);
        $this->assertFilter('/', 'before', 'session');
        $this->assertNotFilter('/login', 'before', 'session');
        
        $result = $this->call('get', '/');
        $result->assertRedirectTo('/login');

    }

    public function testDeveRetornarErroAoTentarLogarComUsuarioInvalido()
    {
        // Definindo dados do usuário
        $dataLogin = ['username'  => 'Invalido', 'password' => 'Invalido'];
        
        // Fazendo a requisição
        $result = $this->call('post', 'login', $dataLogin);
        
        // Testando se a requisição foi realizada
        $result->assertOK();
        
        // Verificando se a requisição foi redirecionada 
        $result->assertRedirectTo('/login');
        
        // Verificando se o usuário não foi criado na sessão 
        $userSession = session()->get('user');
        $this->assertNull($userSession);
    }

    public function testDeveRedirecionarParaDashboardAoLogarComUsuarioDocente()
    {
        // Definindo dados do usuário
        $dataLogin = ['username'  => 'docente', 'password' => 'valido'];

        // Fazendo a requisição
        $result = $this->call('post', 'login', $dataLogin);

        // Testando se a requisição foi realizada
        $result->assertOK();

        // Verificando se houve redirecionamento para a URL base do site (rota protegida)
        $url = $result->getRedirectUrl();
        $this->assertEquals(site_url(), $url); 
        
        // Verificando se foi criado um array na sessão com id e cpf
        $userSession = session()->get('user');
        $this->assertArrayHasKey('id',$userSession);
        $this->assertArrayHasKey('cpf',$userSession);
        $this->assertEquals('1', $userSession['id']);
        $this->assertEquals('00000321654', $userSession['cpf']);

        // Verificando se o usuário foi criado cooretamente no banco de dados e definido na sessão
        $userLogged = auth()->user();
        $this->assertEquals('docente', $userLogged->username);
        $this->assertEquals('emaildocente@unilab.edu.br', $userLogged->getEmail());
        $this->assertEquals(['docente'], $userLogged->getGroups());

        auth()->logout();
    }

    public function testDeveRedirecionarParaDashboardAoLogarComUsuarioDiscente()
    {
        // Definindo dados do usuário
        $dataLogin = ['username'  => 'discente', 'password' => 'valido'];

        // Fazendo a requisição
        $result = $this->call('post', 'login', $dataLogin);

        // Testando se a requisição foi realizada
        $result->assertOK();

        // Verificando se houve redirecionamento para a URL base do site (rota protegida)
        $url = $result->getRedirectUrl();
        $this->assertEquals(site_url(), $url); 
        
        // Verificando se foi criado um array na sessão com id e cpf
        $userSession = session()->get('user');
        $this->assertArrayHasKey('id',$userSession);
        $this->assertArrayHasKey('cpf',$userSession);
        $this->assertEquals('1', $userSession['id']);
        $this->assertEquals('00032165487', $userSession['cpf']);
        

        // Verificando se o usuário foi criado cooretamente no banco de dados e definido na sessão
        $userLogged = auth()->user();
        $this->assertEquals('discente', $userLogged->username);
        $this->assertEquals('discente@aluno.unilab.edu.br', $userLogged->getEmail());
        $this->assertEquals(['discente'], $userLogged->getGroups());

        auth()->logout();
    }
}