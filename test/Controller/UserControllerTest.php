<?php

namespace Pamo\User;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleJsonController.
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
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

        // Setup the enviroment
        $this->session = $di->get("session");
        $this->session->start();
        $this->session->set("testdb", true);

        // Setup the controller
        $this->controller = new UserController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();
    }



    /**
     * Test the route "index".
     */
    public function testIndexActionGet()
    {
        chdir(ANAX_INSTALL_PATH);
        shell_exec("bash reset_testdb.bash");

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
        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\CreateUserForm",
                "username" => "username",
                "email" => "test@mail.com",
                "password" => "test",
                "repeat-password" => "test",
                "submit" => "Create user"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->createAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\CreateUserForm",
                "username" => "username2",
                "email" => "test@mail.com",
                "password" => "no",
                "repeat-password" => "match",
                "submit" => "Create user"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
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
        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\UserLoginForm",
                "username" => "username",
                "password" => "test",
                "submit" => "Login"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
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
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->loginAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "update".
     */
    public function testUpdateAction()
    {
        $this->session->set("user", null);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\UpdateUserForm",
                "id" => 2,
                "username" => "username",
                "email" => "test@mail.com",
                "old-password" => "test",
                "new-password" => "test",
                "repeat-new-password" => "test",
                "submit" => "Save"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->updateAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "username",
            "email" => "doe@mail.com"
        ]);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\UpdateUserForm",
                "id" => 2,
                "username" => "username",
                "email" => "test@mail.com",
                "old-password" => "wrong",
                "new-password" => "test",
                "repeat-new-password" => "test",
                "submit" => "Save"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->updateAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\UpdateUserForm",
                "id" => 2,
                "username" => "username",
                "email" => "test@mail.com",
                "old-password" => "test",
                "new-password" => "test",
                "repeat-new-password" => "wrong",
                "submit" => "Save"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->updateAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\UpdateUserForm",
                "id" => 2,
                "username" => "username",
                "email" => "test@mail.com",
                "old-password" => "test",
                "new-password" => "test",
                "repeat-new-password" => "test",
                "submit" => "Save"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->updateAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "delete".
     */
    public function testDeleteAction()
    {
        $this->session->set("user", null);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\DeleteUserForm",
                "id" => 2,
                "password" => "test",
                "submit" => "Delete Account"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->deleteAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "username",
            "email" => "doe@mail.com"
        ]);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\DeleteUserForm",
                "id" => 2,
                "password" => "wrong",
                "submit" => "Delete Account"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->deleteAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\User\HTMLForm\DeleteUserForm",
                "id" => 2,
                "password" => "test",
                "submit" => "Delete Account"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->deleteAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "invalid".
     */
    public function testInvalidAction()
    {
        $res = $this->controller->invalidAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "ineligible".
     */
    public function testIneligibleAction()
    {
        $res = $this->controller->ineligibleAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "profile".
     */
    public function testProfileAction()
    {
        $this->session->set("user", null);

        $res = $this->controller->profileAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "doe",
            "email" => "doe@mail.com"
        ]);

        $res = $this->controller->profileAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "activity".
     */
    public function testActivityAction()
    {
        $res = $this->controller->activityAction("doe");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "logout".
     */
    public function testLogoutAction()
    {
        $res = $this->controller->logoutAction();
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
