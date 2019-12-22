<?php

namespace Pamo\Question;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Pamo\Question\HTMLForm\CreateQuestionForm;
use Pamo\Question\HTMLForm\DeleteQuestionForm;
use Pamo\Question\HTMLForm\UpdateQuestionForm;



/**
 * Question controller.
 */
class QuestionController implements ContainerInjectableInterface
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
        $this->base = "question";
        $this->title = "Questions";
        $this->game = $this->di->get("game");
        $this->sortBy = $this->game->getQSort();
        $this->sortType = $this->game->getQSortType();
        $this->page = $this->di->get("page");
        $this->page->add("block/nav-admin", $this->game->getNav());
    }



    /**
     * Show all questions.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $groupConcat = "(
            SELECT GROUP_CONCAT(tagname, ',')
            FROM 'Tag2Question'
            WHERE Tag2Question.questionid = Question.id) AS tags";
        $this->page->add($this->base . "/crud/view-all", [
            "title" => "All Questions",
            "question" => $this->game->tagQuestion->joinConcatOrder("Question", "questionid = Question.id", $groupConcat, "Question.id", "$this->sortBy $this->sortType"),
            "answer" => $this->game->answer,
            "activeSort" => "$this->sortBy $this->sortType"
        ]);

        return $this->page->render([
            "title" => $this->title,
        ]);
    }



    /**
     * Show all questions.
     *
     * @return object as a response object
     */
    public function tagActionGet($tag) : object
    {
        $tag = str_replace("-", " ", $tag);
        $groupConcat = "(
            SELECT GROUP_CONCAT(tagname, ',')
            FROM 'Tag2Question'
            WHERE Tag2Question.questionid = Question.id) AS tags";
        $this->page->add($this->base . "/crud/view-all", [
            "title" => "Questions for $tag",
            "question" => $this->game->tagQuestion->joinWhereConcatOrder("tagname = ?", $tag, "Question", "questionid = Question.id", $groupConcat, "Question.id", "$this->sortBy $this->sortType"),
            "answer" => $this->game->answer,
            "activeSort" => "$this->sortBy $this->sortType"
        ]);

        return $this->page->render([
            "title" => "$this->title | Tags",
        ]);
    }



    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $form = new CreateQuestionForm($this->di);
        $form->check();

        $this->page->add($this->base . "/crud/create", [
            "form" => $form->getHTML(),
        ]);

        return $this->page->render([
            "title" => "$this->title | Ask",
        ]);
    }



    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return null
     */
    public function acceptAction(int $id, int $answerId)
    {
        if (!$this->game->activeUser()) {
            return $this->di->get("response")->redirect("user/login");
        }

        $question = $this->db->question;
        $question->find("id", $id);
        $question->accepted = $answerId;

        $question->save();

        $this->di->get("response")->redirect("game/question/$id")->send();
    }



    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return null
     */
    public function unacceptAction(int $id, int $answerId)
    {
        if (!$this->game->activeUser()) {
            return $this->di->get("response")->redirect("user/login");
        }

        $question = $this->db->question;
        $question->find("id", $id);
        $question->accepted = null;

        $question->save();

        $this->di->get("response")->redirect("game/question/$id")->send();
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
