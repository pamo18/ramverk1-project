<?php

namespace Pamo\Comment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Pamo\Comment\CommentQ;
use Pamo\Comment\CommentA;

/**
 * Form to create an item.
 */
class CreateCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $type, $questionid, $answerid)
    {
        parent::__construct($di);
        $this->user = $di->get("session")->get("user", null);
        $this->returnQuestion = $questionid;
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Details of the item",
            ],
            [
                "type" => [
                    "type" => "hidden",
                    "value" => $type,
                ],

                "questionid" => [
                    "type" => "hidden",
                    "value" => $questionid,
                ],

                "answerid" => [
                    "type" => "hidden",
                    "value" => $answerid,
                ],

                "user" => [
                    "type" => "hidden",
                    "value" => $this->user["username"],
                ],

                "text" => [
                    "type" => "text",
                    "label" => false
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Post Comment",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "cancel" => [
                    "type"      => "submit",
                    "value"     => "Cancel",
                    "callback" => [$this, "callbackSuccess"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $type = $this->form->value("type");
        $comment = $type === "question" ? new CommentQ() : new CommentA();
        $comment->setDb($this->di->get("dbqb"));

        if ($type === "answer") {
            $comment->answerid = $this->form->value("answerid");
        }

        $comment->questionid = $this->form->value("questionid");
        $comment->user = $this->form->value("user");
        $comment->text = $this->form->value("text");
        $comment->vote = 0;
        $comment->save();

        $game = $this->di->get("game");
        $game->updateRank($comment->user, 5);

        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("game/question/$this->returnQuestion")->send();
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
