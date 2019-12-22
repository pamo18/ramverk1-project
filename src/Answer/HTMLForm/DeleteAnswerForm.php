<?php

namespace Pamo\Answer\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Pamo\Answer\Answer;

/**
 * Form to delete an item.
 */
class DeleteAnswerForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $answer = $this->getItemDetails($id);
        $this->di->game->validUser($answer->user);
        $this->questionId = $answer->questionid;

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Delete an item",
            ],
            [
                "id" => [
                    "type" => "hidden",
                    "value" => $answer->id,
                ],

                "text" => [
                    "type" => "textarea",
                    "readonly" => true,
                    "label" => false,
                    "value" => $answer->text
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Delete answer",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "cancel" => [
                    "type" => "submit",
                    "value" => "Cancel",
                    "callback" => [$this, "callbackSuccess"]
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
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->find("id", $id);
        return $answer;
    }



    /**
     *
     * @return null
     */
    public function deleteComments($id)
    {
        $game = $this->di->get("game");

        $commentAnswer = $game->commentAnswer;
        $commentsToDelete = $commentAnswer->findAllWhere("answerid = ?", $id);

        foreach ($commentsToDelete as $comment) {
            $commentAnswer->find("id", $comment->id);
            $commentAnswer->delete();
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
        // First delete all comments connected to answer
        $this->deleteComments($id);

        // Now delete the actual answer
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->find("id", $id);
        $answer->delete();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("game/question/$this->questionId")->send();
    }



    // /**
    //  * Callback what to do if the form was unsuccessfully submitted, this
    //  * happen when the submit callback method returns false or if validation
    //  * fails. This method can/should be implemented by the subclass for a
    //  * different behaviour.
    //  */
    // public function callbackFail()
    // {
    //     $this->di->get("response")->redirectSelf()->send();
    // }
}
