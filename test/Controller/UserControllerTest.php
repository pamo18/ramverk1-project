<?php

namespace Pamo\User;

use Anax\DI\DIFactoryConfig;
use Pamo\User\HTMLForm\CreateUserForm;
use Pamo\User\HTMLForm\UserLoginForm;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleJsonController.
 */
class UserControllerTest extends TestCase
{

    // Create the di container.
    protected $di;
    protected $controller;



    /**
     * Prepare before each test.
     */
    protected function setUp()
    {
        global $di;

        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        // Setup the controller
        $this->controller = new UserController();
        $this->controller->setDI($this->di);
        $session = $di->get("session");
        $session->start();
        $session->set("testdb", true);
    }



    /**
     * Test the route "index".
     */
    public function testIndexActionGet()
    {
        $res = $this->controller->indexActionGet();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "create".
     */
    public function testCreateAction()
    {
        shell_exec("sqlite3 " . ANAX_INSTALL_PATH . "/data/db_test.sqlite < sql/reset/user_reset.sql");

        $res = $this->controller->createAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $_SERVER["REQUEST_METHOD"] = "POST";

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\CreateUserForm",
                "username" => "test1",
                "password" => "test",
                "repeat-password" => "test",
                "submit" => "Create user"
            ]
        ]);

        $res = $this->controller->createAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\CreateUserForm",
                "username" => "test2",
                "password" => "no",
                "repeat-password" => "match",
                "submit" => "Create user"
            ]
        ]);

        $res = $this->controller->createAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "login".
     */
    public function testLoginAction()
    {
        $res = $this->controller->loginAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $_SERVER["REQUEST_METHOD"] = "POST";

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\UserLoginForm",
                "username" => "test1",
                "password" => "test",
                "submit" => "Login"
            ]
        ]);

        $res = $this->controller->loginAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\UserLoginForm",
                "username" => "test1",
                "password" => "wrong",
                "submit" => "Login"
            ]
        ]);

        $res = $this->controller->loginAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "dump-di".
     */
    public function testDumpDiActionGet()
    {
        $res = $this->controller->dumpDiActionGet();
        $this->assertContains("di contains", $res);
        $this->assertContains("configuration", $res);
        $this->assertContains("request", $res);
        $this->assertContains("response", $res);
    }



    /**
     * Call the controller catchAll ANY.
     */
    public function testCatchAllGet()
    {
        $res = $this->controller->catchAll();
        $this->assertNull($res);
    }
}
