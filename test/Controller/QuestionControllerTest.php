<?php

namespace Pamo\Question;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleJsonController.
 */
class QuestionControllerTest extends TestCase
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
        $this->controller = new QuestionController();
        $this->controller->setDI($this->di);

        $this->di->get("request")->setGlobals([
            "get" => [
                "sortQBy" => "vote",
                "sortQType" => "ASC"
            ]
        ]);

        $this->controller->initialize();
        $this->session = $di->get("session");
        $this->session->start();
        $this->session->set("testdb", true);
    }



    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexActionGet();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "tag".
     */
    public function testTagAction()
    {
        $res = $this->controller->tagActionGet("Nintendo");
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
                "anax/htmlform-id" => "Pamo\Question\HTMLForm\CreateQuestionForm",
                "user" => "doe",
                "title" => "test",
                "question" => "test",
                "tags" => "test",
                "submit" => "Submit"
            ]
        ]);

        $_SERVER["REQUEST_METHOD"] = "POST";

        $res = $this->controller->createAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\Question\HTMLForm\CreateQuestionForm",
                "user" => "doe",
                "title" => "testing",
                "question" => "testing",
                "tags" => "testing",
                "submit" => "Submit"
            ]
        ]);

        $_SERVER["REQUEST_METHOD"] = "POST";

        $res = $this->controller->createAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "post" => [
                "anax/htmlform-id" => "Pamo\Question\HTMLForm\CreateQuestionForm",
                "user" => "doe",
                "title" => "tested",
                "question" => "tested",
                "tags" => null,
                "submit" => "Submit"
            ]
        ]);

        $_SERVER["REQUEST_METHOD"] = "POST";

        $res = $this->controller->createAction();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "accept".
     */
    public function testAcceptAction()
    {
        $this->session->set("user", null);

        $res = $this->controller->acceptAction(1, 1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "doe",
            "email" => "doe@mail.com"
        ]);

        $res = $this->controller->acceptAction(1, 1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "unaccept".
     */
    public function testUnacceptAction()
    {
        $this->session->set("user", null);

        $res = $this->controller->unacceptAction(1, 1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "doe",
            "email" => "doe@mail.com"
        ]);

        $res = $this->controller->unacceptAction(1, 1);
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
