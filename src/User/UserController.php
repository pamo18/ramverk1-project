<?php

namespace Pamo\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Pamo\User\HTMLForm\UserLoginForm;

/**
 * User controller.
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        $this->base = "user";
        $this->title = "Users";
        $this->game = $this->di->get("game");
        $this->page = $this->di->get("page");
        $this->page->add("block/nav-admin", $this->game->getNav());
    }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $this->page->add($this->base . "/crud/view-all", [
            "title" => "All Users",
            "users" => $this->game->user->findAll(),
            "gravatar" => $this->game->gravatar
        ]);

        return $this->page->render([
            "title" => $this->title,
        ]);
    }



    /**
     * Description.
     *
     * @param string $username of user
     *
     *
     * @return object as a response object
     */
    public function invalidAction() : object
    {
        $this->page->add($this->base . "/invalid", [
            "title" => "Invalid user"
        ]);

        return $this->page->render([
            "title" => $this->title,
        ]);
    }



    /**
     * Description.
     *
     * @param string $username of user
     *
     *
     * @return object as a response object
     */
    public function ineligibleAction() : object
    {
        $this->page->add($this->base . "/ineligible", [
            "title" => "Ineligible"
        ]);

        return $this->page->render([
            "title" => $this->title,
        ]);
    }



    /**
     * Description.
     *
     * @param string $username of user
     *
     *
     * @return object as a response object
     */
    public function profileAction() : object
    {
        if (!$this->game->activeUser()) {
            return $this->di->get("response")->redirect("user/login");
        }

        $username = $this->game->activeUser("username");

        $this->page->add($this->base . "/profile", [
            "title" => "Profile page for $username",
            "user" => $this->game->user->findWhere("username = ?", $username)
        ]);

        return $this->page->render([
            "title" => $this->title,
        ]);
    }



    /**
     * Description.
     *
     * @param string $username of user
     *
     *
     * @return object as a response object
     */
    public function activityAction($username) : object
    {
        $select1 = "*, CommentQuestion.id AS commentid, CommentQuestion.text AS comment, CommentQuestion.created AS commentdate, CommentQuestion.user AS commentuser";
        $select2 = "*, CommentAnswer.id AS commentid, CommentAnswer.text AS comment, CommentAnswer.created AS commentdate, CommentAnswer.user AS commentuser";

        $data = [
            "title" => "Activity page for $username",
            "user" => $this->game->user->findWhere("username = ?", $username),
            "questions" => $this->game->question->findAllWhere("user = ?", $username),
            "answers" => $this->game->answer->findAllWhere("user = ?", $username),
            "commentQuestions" => $this->game->commentQuestion->joinWhere("CommentQuestion.user = ?", $username, "Question", "Question.id = CommentQuestion.questionid", $select1),
            "commentAnswers" => $this->game->commentAnswer->joinWhere("CommentAnswer.user = ?", $username, "Answer", "Answer.id = CommentAnswer.answerid", $select2),
            "gravatar" => $this->game->gravatar
        ];

        $this->page->add($this->base . "/activity/header", $data);
        $this->page->add($this->base . "/activity/questions", $data);
        $this->page->add($this->base . "/activity/answers", $data);
        $this->page->add($this->base . "/activity/question-comments", $data);
        $this->page->add($this->base . "/activity/answer-comments", $data);

        return $this->page->render([
            "title" => $this->title,
        ]);
    }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function loginAction() : object
    {
        $form = new UserLoginForm($this->di);
        $form->check();

        $this->page->add("block/header", [
            "title" => "Login",
        ]);

        $this->page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $this->page->render([
            "title" => "A login page",
        ]);
    }



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $form = $this->game->getUserForm("create");
        $form->check();

        $this->page->add("block/header", [
            "title" => "Register",
        ]);

        $this->page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $this->page->render([
            "title" => "A create user page",
        ]);
    }



    /**
     * Description.
     *
     * @return object as a response object
     */
    public function updateAction() : object
    {
        if (!$this->game->activeUser()) {
            return $this->di->get("response")->redirect("user/login");
        }

        $username = $this->game->activeUser("username");

        $form = $this->game->getUserForm("edit", $username);
        $form->check();

        $this->page->add("block/header", [
            "title" => "Update Profile",
        ]);

        $this->page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $this->page->render([
            "title" => "Update profile",
        ]);
    }



    /**
     * Description.
     *
     * @return object as a response object
     */
    public function deleteAction() : object
    {
        if (!$this->game->activeUser()) {
            return $this->di->get("response")->redirect("user/login");
        }

        $username = $this->game->activeUser("username");

        $form = $this->game->getUserForm("delete", $username);
        $form->check();

        $this->page->add("block/header", [
            "title" => "Delete Account",
        ]);

        $this->page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $this->page->render([
            "title" => "Delete profile",
        ]);
    }



    /**
     * Description.
     *
     * @return null
     */
    public function logoutAction() : object
    {
        $this->di->get("session")->set("user", null);

        return $this->di->get("response")->redirect("user/login")->send();
    }

    /**
     * This sample method dumps the content of $di.
     * GET mountpoint/dump-app
     *
     * @return string
     */
    public function dumpDiActionGet() : string
    {
        // Deal with the action and return a response.
        $services = implode(", ", $this->di->getServices());
        return __METHOD__ . "<p>\$di contains: $services";
    }



    /**
     * Adding an optional catchAll() method will catch all actions sent to the
     * router. You can then reply with an actual response or return void to
     * allow for the router to move on to next handler.
     * A catchAll() handles the following, if a specific action method is not
     * created:
     * ANY METHOD mountpoint/**
     *
     * @param array $args as a variadic parameter.
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        // Deal with the request and send an actual response, or not.
        //return __METHOD__ . ", \$db is {$this->db}, got '" . count($args) . "' arguments: " . implode(", ", $args);
        return;
    }
}
