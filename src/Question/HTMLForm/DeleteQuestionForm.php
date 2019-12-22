<?php

namespace Pamo\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Pamo\Question\Question;

/**
 * Form to delete an item.
 */
class DeleteQuestionForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $question = $this->getItemDetails($id);
        $this->questionid = $question->id;
        $this->di->game->validUser($question->user);

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Delete an item",
            ],
            [
                "id" => [
                    "type" => "hidden",
                    "value" => $question->id,
                ],

                "title" => [
                    "type" => "text",
                    "readonly" => true,
                    "label" => false,
                    "value" => "$question->title"
                ],

                "text" => [
                    "type" => "textarea",
                    "readonly" => true,
                    "label" => false,
                    "value" => "$question->text"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Delete question",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "cancel" => [
                    "type" => "submit",
                    "value" => "Cancel",
                    "callback" => [$this, "callbackFail"]
                ],
            ]
        );
    }



    /**
     * Get all items.
     *
     * @return object with key value of all items.
     */
    protected function getItemDetails($id) : object
    {
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $id);
        return $question;
    }



    /**
     *
     * @return null
     */
    public function deleteAnswers($id)
    {
        $game = $this->di->get("game");
        $answer = $game->answer;
        $answersToDelete = $answer->findAllWhere("questionid = ?", $id);

        foreach($answersToDelete as $ans) {
            $answer->find("id", $ans->id);
            $answer->delete();
        }
    }



    /**
     *
     * @return null
     */
    public function deleteAnswerComments($id)
    {
        $game = $this->di->get("game");
        $commentAnswer = $game->commentAnswer;
        $commentsToDelete = $commentAnswer->findAllWhere("questionid = ?", $id);

        foreach($commentsToDelete as $comment) {
            $commentAnswer->find("id", $comment->id);
            $commentAnswer->delete();
        }
    }



    /**
     *
     * @return null
     */
    public function deleteQuestionComments($id)
    {
        $game = $this->di->get("game");
        $commentQuestion = $game->commentQuestion;
        $commentsToDelete = $commentQuestion->findAllWhere("questionid = ?", $id);

        foreach($commentsToDelete as $comment) {
            $commentQuestion->find("id", $comment->id);
            $commentQuestion->delete();
        }
    }



    /**
     *
     * @return null
     */
    public function deleteQuestionTags($id)
    {
        $game = $this->di->get("game");
        $tagQuestion = $game->tagQuestion;
        $tagsToDelete = $tagQuestion->findAllWhere("questionid = ?", $id);

        foreach($tagsToDelete as $tag) {
            $tagQuestion->find("id", $tag->id);
            $tagQuestion->delete();
        }
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $id = $this->form->value("id");

        // First, delete all comments connected to question
        $this->deleteQuestionComments($id);
        $this->deleteAnswerComments($id);

        // Second, delete all answers connected to question
        $this->deleteAnswers($id);

        // Third, keep all tags but delete all links between tags and the question question
        $this->deleteQuestionTags($id);

        // Last, delete the actual question
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $id);
        $question->delete();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("question")->send();
    }



    /**
     * Callback what to do if the form was unsuccessfully submitted, this
     * happen when the submit callback method returns false or if validation
     * fails. This method can/should be implemented by the subclass for a
     * different behaviour.
     */
    public function callbackFail()
    {
        $this->di->get("response")->redirect("game/question/$this->questionid")->send();
    }
}
