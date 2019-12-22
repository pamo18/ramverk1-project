<?php

namespace Pamo\Game;

use Pamo\Question\HTMLForm\CreateQuestionForm;
use Pamo\Question\HTMLForm\DeleteQuestionForm;
use Pamo\Question\HTMLForm\UpdateQuestionForm;
use Pamo\Answer\HTMLForm\CreateAnswerForm;
use Pamo\Answer\HTMLForm\DeleteAnswerForm;
use Pamo\Answer\HTMLForm\UpdateAnswerForm;
use Pamo\Comment\HTMLForm\CreateCommentForm;
use Pamo\Comment\HTMLForm\DeleteCommentForm;
use Pamo\Comment\HTMLForm\UpdateCommentForm;

/**
 * Game
 */
class Game
{
    /**
     * @var object $di.
     */
    private $di;



    /**
     * Game class
     *
     */
    public function init($di)
    {
        $this->di = $di;
        $this->game = [];
        $this->session = $this->di->get("session");
        $this->request = $this->di->get("request");
        $this->gravatar = $this->di->get("gravatar");
        $this->sortQBy = $this->session->get("sortQBy", "created");
        $this->sortQType = $this->session->get("sortQType", "DESC");
        $this->sortABy = $this->session->get("sortABy", "created");
        $this->sortAType = $this->session->get("sortAType", "ASC");
    }



    /**
     * Get and setup all databases.
     *
     */
    public function getDatabases()
    {
        $this->question = $this->di->get("question");
        $this->question->setDb($this->di->get("dbqb"));

        $this->answer = $this->di->get("answer");
        $this->answer->setDb($this->di->get("dbqb"));

        $this->user = $this->di->get("user");
        $this->user->setDb($this->di->get("dbqb"));

        $this->commentQuestion = $this->di->get("comment-question");
        $this->commentQuestion->setDb($this->di->get("dbqb"));

        $this->commentAnswer = $this->di->get("comment-answer");
        $this->commentAnswer->setDb($this->di->get("dbqb"));

        $this->tag = $this->di->get("tag");
        $this->tag->setDb($this->di->get("dbqb"));

        $this->tagQuestion = $this->di->get("tag-question");
        $this->tagQuestion->setDb($this->di->get("dbqb"));
    }



    /**
     * Vote
     *
     * @return object
     */
    public function vote($type, $id, $vote)
    {
        if (!$this->activeUser()) {
            return $this->di->get("response")->redirect("user/login");
        }

        $currentUser = $this->session->get("user", null);

        switch ($type) {
            case "question":
                $table = $this->question;
                break;
            case "answer":
                $table = $this->answer;
                break;
            case "comment-question":
                $table = $this->commentQuestion;
                break;
            case "comment-answer":
                $table = $this->commentAnswer;
                break;
        }

        $table->find("id", $id);

        $eligible = $this->canUserVote($table->user);
        if (!$eligible) {
            return $this->di->get("response")->redirect("user/ineligible")->send();
        }

        if ($vote === "up") {
            $table->vote += 1;
            $this->updateRank($table->user, $table->vote);
        } else if ($vote === "down") {
            $table->vote -= 1;
        } else {
            $table->vote = $table->vote;
        }

        $table->save();

        if ($type === "question") {
            $questionId = $table->id;
        } else {
            $questionId = $table->questionid;
        }

        $user = $this->user;
        $user->find("username", $currentUser["username"]);
        $user->voted += 1;
        $user->save();
        $this->updateRank($currentUser["username"], 1);

        $this->di->get("response")->redirect("game/question/$questionId")->send();
    }

    /**
     * Rank
     *
     * @return object
     */
    public function updateRank($username, $rank) {
        switch (true) {
            case $rank <= 1:
                $value = 1;
                break;
            case $rank <= 5:
                $value = 5;
                break;
            case $rank <= 10:
                $value = 10;
                break;
            case $rank <= 20:
                $value = 20;
                break;
            case $rank <= 30:
                $value = 30;
                break;
        }

        $user = $this->user;
        $user->find("username", $username);
        $user->rank += $value;
        $user->save();
    }



    /**
     * Return question form
     *
     * @return object
     */
    public function getQuestionForm($adminType, $questionId = null)
    {
        $request = $this->di->get("request");
        $adminId = $request->getGet("adminId", null);

        switch ($adminType) {
            case "create";
                $questionForm = new CreateQuestionForm($this->di);
                break;
            case "edit";
                $questionForm = new UpdateQuestionForm($this->di, $questionId);
                break;
            case "delete";
                $questionForm = new DeleteQuestionForm($this->di, $questionId);
                break;
            default:
                $questionForm = null;
        }
        return $questionForm;
    }



    /**
     * Return answer form
     *
     * @return object
     */
    public function getAnswerForm($adminType, $questionId)
    {
        $request = $this->di->get("request");
        $adminId = $request->getGet("adminId", null);

        switch ($adminType) {
            case "create";
                $answerForm = new CreateAnswerForm($this->di, $questionId, $this->activeUser("username"));
                break;
            case "edit";
                $answerForm = new UpdateAnswerForm($this->di, $adminId);
                break;
            case "delete";
                $answerForm = new DeleteAnswerForm($this->di, $adminId);
                break;
            default:
                $answerForm = null;
        }
        return $answerForm;
    }



    /**
     * Return comment form
     *
     * @return object
     */
    public function getCommentForm($adminType, $questionId, $table)
    {
        $request = $this->di->get("request");
        $adminId = $request->getGet("adminId", null);
        $answerId = $request->getGet("answerId", null);

        switch ($adminType) {
            case "create";
                $commentForm = new CreateCommentForm($this->di, $table, $questionId, $answerId);
                break;
            case "edit";
                $commentForm = new UpdateCommentForm($this->di, $table, $adminId);
                break;
            case "delete";
                $commentForm = new DeleteCommentForm($this->di, $table, $adminId);
                break;
            default:
                $commentForm = null;
        }
        return $commentForm;
    }



    /**
     * Return nav
     *
     * @return array
     */
    public function getNav()
    {
        $navData = [];

        if ($this->activeUser()) {
            $navData = [
                "activeUser" => $this->activeUser(),
                "gravatar" => $this->gravatar->get($this->activeUser("email"))
            ];
        }

        return $navData;
    }



    /**
     * Return form settings
     *
     * @return array
     */
    public function getFormSettings()
    {
        $formSettings = [
            "use_fieldset" => false
        ];

        return $formSettings;
    }



    /**
     * Return current user
     *
     * @return array
     */
    public function activeUser($item = null)
    {
        $user = $this->session->get("user", null);

        if ($item && $user) {
            return $user[$item];
        }

        return $user;
    }



    /**
     * Is the logged in user the content owner.
     *
     * @return null
     */
    public function validUser($username)
    {
        if ($this->activeUser("username") != $username) {
            $this->di->get("response")->redirect("user/invalid")->send();
        }
    }



    /**
     * Is the logged in user the content owner.
     *
     * @return null
     */
    public function canUserVote($username)
    {
        return $this->activeUser("username") != $username;
    }



    /**
     * Return question sort
     *
     * @return string
     */
    public function getQSort()
    {
        $sort = $this->request->getGet("sortQBy", null);

        if ($sort) {
            $this->session->set("sortQBy", $sort);
            $this->sortQBy = $sort;
        }

        return $this->sortQBy;
    }



    /**
     * Return question sort type
     *
     * @return string
     */
    public function getQSortType()
    {
        $sortType = $this->request->getGet("sortQType", null);

        if ($sortType) {
            $this->session->set("sortQType", $sortType);
            $this->sortQType = $sortType;
        }

        return $this->sortQType;
    }



    /**
     * Return answer sort
     *
     * @return string
     */
    public function getASort()
    {
        $sort = $this->request->getGet("sortABy", null);

        if ($sort) {
            $this->session->set("sortABy", $sort);
            $this->sortABy = $sort;
        }

        return $this->sortABy;
    }



    /**
     * Return answer sort type
     *
     * @return string
     */
    public function getASortType()
    {
        $sortType = $this->request->getGet("sortAType", null);

        if ($sortType) {
            $this->session->set("sortAType", $sortType);
            $this->sortAType = $sortType;
        }

        return $this->sortAType;
    }
}
