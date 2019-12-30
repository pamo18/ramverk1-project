<?php

namespace Pamo\Game;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Game controller
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class GameController implements ContainerInjectableInterface
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
        $this->base = "game";
        $this->title = "Q & A";
        $this->game = $this->di->get("game");
        $this->sortBy = $this->game->getASort();
        $this->sortType = $this->game->getASortType();

        $this->data = [
            "textFilter" => $this->di->get("textfilter"),
            "gravatar" => $this->di->get("gravatar"),
            "user" => $this->game->user,
            "activeSort" => "$this->sortBy $this->sortType"
        ];

        $this->page = $this->di->get("page");
        $this->page->add("block/nav-admin", $this->game->getNav());
        $this->request = $this->di->get("request");
    }



    /**
     * Show questions, answers and comments.
     *
     * @return object as a response object
     */
    public function questionAction(int $questionId) : object
    {
        $data = array_merge([
            "question" => $this->game->question->findById($questionId),
            "answers" => $this->game->answer->findAllWhereOrder("questionid = ?", $questionId, "$this->sortBy $this->sortType"),
            "commentQuestion" => $this->game->commentQuestion->findAllWhere("questionid = ?", $questionId),
            "commentAnswer" => $this->game->commentAnswer,
            "tags" => $this->game->tagQuestion->findAllWhere("questionid = ?", $questionId)
        ], $this->data);

        if ($this->game->activeUser()) {
            $request = $this->di->get("request");
            $admin = $request->getGet("admin", null);
            $adminType = $request->getGet("adminType", null);
            $adminId = $request->getGet("adminId", null);

            $answerForm = $this->game->getAnswerForm("create", $questionId);
            $answerForm->check();

            switch ($admin) {
                case "question":
                    if ($adminType === "create") {
                        return $this->di->get("response")->redirect("question/create")->send();
                    }
                    $owner = $this->game->question->findById($questionId)->user;
                    $adminForm = $this->game->getQuestionForm($adminType, $questionId);
                    break;
                case "answer":
                    $owner = $this->game->answer->findById($adminId)->user;
                    $adminForm = $this->game->getAnswerForm($adminType, $questionId);
                    break;
                case "comment-question":
                    $owner = $this->game->commentQuestion->findById($adminId)->user;
                    $adminForm = $this->game->getCommentForm($adminType, $questionId, "question");
                    break;
                case "comment-answer":
                    $owner = $this->game->commentAnswer->findById($adminId)->user;
                    $adminForm = $this->game->getCommentForm($adminType, $questionId, "answer");
                    break;
            }

            if (isset($owner) && !$this->game->validUser($owner)) {
                return $this->di->get("response")->redirect("user/invalid")->send();
            }

            if (isset($adminForm)) {
                $adminForm->check();
            }

            $data["answerForm"] = $answerForm->getHTML($this->game->getFormSettings());
            $data["admin"] = $admin;
            $data["adminForm"] = isset($adminForm) ? $adminForm->getHTML($this->game->getFormSettings()) : null;
            $data["adminAnswerId"] = $admin === "answer" ? $request->getGet("adminId") : $request->getGet("answerId", null);
        }

        if ($this->game->activeUser()) {
            $this->page->add($this->base . "/block/question-admin", $data);
            $this->page->add($this->base . "/block/answer-sort", $data);
            $this->page->add($this->base . "/block/answer-admin", $data);
        } else {
            $this->page->add($this->base . "/block/question-no-admin", $data);
            $this->page->add($this->base . "/block/answer-sort", $data);
            $this->page->add($this->base . "/block/answer-no-admin", $data);
        }

        return $this->page->render([
            "title" => $this->title,
        ]);
    }



    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     * @param string $vote the answer up or down 1.
     *
     * @return null as a response object
     */
    public function voteAction(string $type, int $id, string $vote)
    {
        if (!$this->game->activeUser()) {
            return $this->di->get("response")->redirect("user/login");
        }

        $res = $this->game->vote($type, $id, $vote);

        if (!$res) {
            return $this->di->get("response")->redirect("user/ineligible");
        }

        return $this->di->get("response")->redirect("game/question/$res");
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
