<?php

namespace Pamo\Game;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleJsonController.
 */
class GameControllerTest extends TestCase
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
        $this->controller = new GameController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();
        $this->session = $di->get("session");
        $this->session->start();
        $this->session->set("testdb", true);
    }



    /**
     * Test the route "vote".
     */
    public function testVoteAction()
    {
        $res = $this->controller->voteAction("question", 1, "down");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "doe",
            "email" => "doe@mail.com"
        ]);

        $res = $this->controller->voteAction("question", 1, "up");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "doe",
            "email" => "doe@mail.com"
        ]);

        $res = $this->controller->voteAction("answer", 2, "down");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "testing",
            "email" => "doe@mail.com"
        ]);

        $res = $this->controller->voteAction("comment-question", 1, "up");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $res = $this->controller->voteAction("comment-answer", 1, "up");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $res = $this->controller->voteAction("question", 1, "");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "index".
     */
    public function testQuestionAction()
    {
        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "doe",
            "email" => "doe@mail.com"
        ]);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "question",
                "adminType" => "edit"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "answer",
                "adminType" => "edit"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "comment-question",
                "adminType" => "edit"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "comment-answer",
                "adminType" => "edit"
            ]
        ]);

        $res = $this->controller->questionAction(1);
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
