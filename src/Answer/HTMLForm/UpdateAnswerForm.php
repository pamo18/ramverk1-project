<?php

namespace Pamo\Answer\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Pamo\Answer\Answer;

/**
 * Form to update an item.
 */
class UpdateAnswerForm extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
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
                "legend" => "Update details of the item",
            ],
            [
                "id" => [
                    "type" => "hidden",
                    "readonly" => true,
                    "value" => $answer->id,
                ],

                "questionid" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => $answer->questionid,
                ],

                "text" => [
                    "type" => "textarea",
                    "label" => false,
                    "value" => $answer->text,
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Save",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "cancel" => [
                    "type" => "submit",
                    "value" => "Cancel",
                    "callback" => [$this, "callbackSuccess"],
                ],
            ]
        );
    }



    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return Answer
     */
    public function getItemDetails($id) : object
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->find("id", $id);
        return $answer;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->find("id", $this->form->value("id"));
        $answer->text = $this->form->value("text");
        $answer->save();
        return true;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function vote($id, $vote) : bool
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->find("id", $id);

        if ($vote === "up") {
            $answer->vote += 1;
        } else if ($vote === "down") {
            $answer->vote -= 1;
        } else {
            $answer->vote = $answer->vote;
        }

        $answer->save();

        $this->di->get("response")->redirect("game/question/$answer->questionid")->send();
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
