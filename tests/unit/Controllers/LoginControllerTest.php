<?php

use App\Controllers\LoginController;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\FilterTestTrait;

class LoginControllerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    // use FeatureTestTrait;
    // use FilterTestTrait;
    use ControllerTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    public function testDeveRetornarUmCPFFormatado()
    {
        $obj       = new LoginController();
        $reflector = new ReflectionClass($obj);
        $metodo    = $reflector->getMethod('formatCPF');
        $metodo->setAccessible(true);
        $resultado = $metodo->invoke($obj, 321654);
        $this->assertEquals('00000321654', $resultado);
    }

    public function testDeveRetornarTrueParaDadosDeAlunoComStatusDiscente()
    {
        $aluno = json_decode('[
            {
                "id_status_discente": 1
            }
        ]');
        $obj       = new LoginController();
        $reflector = new ReflectionClass($obj);
        $metodo    = $reflector->getMethod('checkUserIsStudent');
        $metodo->setAccessible(true);
        $resultado = $metodo->invoke($obj, $aluno);
        $this->assertEquals(true, $resultado);
    }
    public function testDeveRetornarTrueParaDadosDeAlunoSemStatusDiscente()
    {
        $aluno = json_decode('[
            {
                "id_status_discente": null
            }
        ]');
        $obj       = new LoginController();
        $reflector = new ReflectionClass($obj);
        $metodo    = $reflector->getMethod('checkUserIsStudent');
        $metodo->setAccessible(true);
        $resultado = $metodo->invoke($obj, $aluno);
        $this->assertEquals(false, $resultado);
    }

    public function testDeveRetornarTrueParaDadosDeDocenteComTipoUsuarioUmECategoriaUm()
    {
        $teacher = json_decode('[
            {
                "id_tipo_usuario": 1,
                "id_categoria": 1
            }
        ]');
        $obj       = new LoginController();
        $reflector = new ReflectionClass($obj);
        $metodo    = $reflector->getMethod('checkUserIsTeacher');
        $metodo->setAccessible(true);
        $resultado = $metodo->invoke($obj, $teacher);
        $this->assertEquals(true, $resultado);
    }
    public function testDeveRetornarFalseParaDadosDeDocenteComCategoriaDois()
    {
        $teacher = json_decode('[
            {
                "id_tipo_usuario": 1,
                "id_categoria": 2
            }
        ]');
        $obj       = new LoginController();
        $reflector = new ReflectionClass($obj);
        $metodo    = $reflector->getMethod('checkUserIsTeacher');
        $metodo->setAccessible(true);
        $resultado = $metodo->invoke($obj, $teacher);
        $this->assertEquals(false, $resultado);
    }

    public function testDeveRetornarFalseParaDadosDeDocenteComTTipoUsuarioDois()
    {
        $teacher = json_decode('[
            {
                "id_tipo_usuario": 2,
                "id_categoria": 1
            }
        ]');
        $obj       = new LoginController();
        $reflector = new ReflectionClass($obj);
        $metodo    = $reflector->getMethod('checkUserIsTeacher');
        $metodo->setAccessible(true);
        $resultado = $metodo->invoke($obj, $teacher);
        $this->assertEquals(false, $resultado);
    }
}
