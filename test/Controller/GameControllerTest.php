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

        // Setup the enviroment
        $this->session = $di->get("session");
        $this->session->start();
        $this->session->set("testdb", true);

        $this->di->get("request")->setGlobals([
            "get" => [
                "sortABy" => "vote",
                "sortAType" => "ASC"
            ]
        ]);

        // Setup the controller
        $this->controller = new GameController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();
    }



    /**
     * Test the route "vote".
     */
    public function testVoteAction()
    {
        $this->session->set("user", [
            "username" => "doe",
            "email" => "doe@mail.com"
        ]);

        $game = $this->di->game;
        $game->updateRank("doe", 5);

        $game = $this->di->game;
        $game->updateRank("doe", 10);

        $game = $this->di->game;
        $game->updateRank("doe", 20);

        $game = $this->di->game;
        $game->updateRank("doe", 30);

        $game = $this->di->game;
        $game->updateRank("doe", 40);

        $res = $this->controller->voteAction("question", 1, "up");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $res = $this->controller->voteAction("question", 4, "down");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $res = $this->controller->voteAction("question", 4, "up");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $res = $this->controller->voteAction("answer", 1, "down");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $res = $this->controller->voteAction("comment-question", 1, "up");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $res = $this->controller->voteAction("comment-answer", 2, "up");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "user",
            "email" => "doe@mail.com"
        ]);

        $res = $this->controller->voteAction("question", 1, "");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", null);

        $res = $this->controller->voteAction("question", 1, "up");
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "question".
     */
    public function testQuestionAction()
    {
        $this->session->set("user", null);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "doe",
            "email" => "doe@mail.com"
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "index".
     */
    public function testQuestionForm()
    {
        $this->session->set("user", [
            "username" => "doe",
            "email" => "doe@mail.com"
        ]);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "question",
                "adminType" => "edit"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Question\HTMLForm\UpdateQuestionForm",
                "id" => 1,
                "user" => "doe",
                "title" => "testing",
                "text" => "testing",
                "submit" => "Save"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "question",
                "adminType" => "delete"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Question\HTMLForm\DeleteQuestionForm",
                "id" => 1,
                "submit" => "Delete question"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        chdir(ANAX_INSTALL_PATH);
        shell_exec("bash script/reset_testdb.bash");
    }



    /**
     * Test the route "index".
     */
    public function testAnswerForm()
    {
        $this->di->request->setGlobals([
            "get" => [
                "admin" => "answer",
                "adminType" => "create"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Answer\HTMLForm\CreateAnswerForm",
                "questionid" => 1,
                "user" => "doe",
                "answer" => "testing",
                "submit" => "Post Your Answer"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->session->set("user", [
            "username" => "pamo18",
            "email" => "doe@mail.com"
        ]);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "answer",
                "adminType" => "edit"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Answer\HTMLForm\UpdateAnswerForm",
                "id" => 1,
                "questionid" => 1,
                "text" => "testing",
                "submit" => "Save"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "answer",
                "adminType" => "delete"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Answer\HTMLForm\DeleteAnswerForm",
                "id" => 1,
                "submit" => "Delete answer"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        chdir(ANAX_INSTALL_PATH);
        shell_exec("bash script/reset_testdb.bash");
    }



    /**
     * Test the route "index".
     */
    public function testCommentQuestionForm()
    {
        $this->di->request->setGlobals([
            "get" => [
                "admin" => "comment-question",
                "adminType" => "create"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Comment\HTMLForm\CreateCommentForm",
                "type" => "question",
                "questionid" => 1,
                "answerid" => 1,
                "user" => "doe",
                "text" => "testing",
                "submit" => "Post Comment"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

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
                "admin" => "comment-question",
                "adminType" => "edit"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Comment\HTMLForm\UpdateCommentForm",
                "id" => 3,
                "type" => "question",
                "text" => "testing",
                "submit" => "Save"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "comment-question",
                "adminType" => "delete"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Comment\HTMLForm\DeleteCommentForm",
                "id" => 3,
                "type" => "question",
                "submit" => "Delete comment"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        chdir(ANAX_INSTALL_PATH);
        shell_exec("bash script/reset_testdb.bash");
    }



    /**
     * Test the route "index".
     */
    public function testCommentAnswerForm()
    {
        $this->di->request->setGlobals([
            "get" => [
                "admin" => "comment-answer",
                "adminType" => "create"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Comment\HTMLForm\CreateCommentForm",
                "type" => "answer",
                "questionid" => 1,
                "answerid" => 1,
                "user" => "doe",
                "text" => "testing",
                "submit" => "Post Comment"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

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
                "admin" => "comment-answer",
                "adminType" => "edit"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Comment\HTMLForm\UpdateCommentForm",
                "id" => 3,
                "type" => "answer",
                "text" => "testing",
                "submit" => "Save"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "comment-answer",
                "adminType" => "delete"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Comment\HTMLForm\DeleteCommentForm",
                "id" => 3,
                "type" => "answer",
                "submit" => "Delete comment"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        chdir(ANAX_INSTALL_PATH);
        shell_exec("bash script/reset_testdb.bash");
    }



    /**
     * Test the route "index".
     */
    public function testInvalidUserForm()
    {
        $this->session->set("user", [
            "username" => "pamo18",
            "email" => "doe@mail.com"
        ]);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "question",
                "adminType" => "edit"
            ],
            "post" => [
                "anax/htmlform-id" => "Pamo\Question\HTMLForm\UpdateQuestionForm",
                "id" => 1,
                "user" => "doe",
                "title" => "testing",
                "text" => "testing",
                "submit" => "Save"
            ],
            "server" => [
                "REQUEST_METHOD" => "POST"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "question",
                "adminType" => "create"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }



    /**
     * Test the route "index".
     */
    public function testUnknownForm()
    {
        $this->di->request->setGlobals([
            "get" => [
                "admin" => "question",
                "adminType" => "unknown"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "answer",
                "adminType" => "unknown"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);


        $this->di->request->setGlobals([
            "get" => [
                "admin" => "comment-question",
                "adminType" => "unknown"
            ]
        ]);

        $res = $this->controller->questionAction(1);
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);

        $this->di->request->setGlobals([
            "get" => [
                "admin" => "comment-answer",
                "adminType" => "unknown"
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
