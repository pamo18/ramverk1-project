<?php

namespace Pamo\Comment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Pamo\Comment\CommentQ;
use Pamo\Comment\CommentA;

/**
 * Form to delete an item.
 */
class DeleteCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $type, $commentId)
    {
        parent::__construct($di);
        $comment = $this->getItemDetails($type, $commentId);
        $this->di->game->validUser($comment->user);
        $this->returnQuestion = $comment->questionid;

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Delete a comment",
            ],
            [
                "id" => [
                    "type" => "hidden",
                    "value" => $comment->id,
                ],

                "type" => [
                    "type" => "hidden",
                    "value" => $type,
                ],

                "text" => [
                    "type" => "text",
                    "readonly" => true,
                    "label" => false,
                    "value" => $comment->text
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Delete comment",
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
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return $comment
     */
    public function getItemDetails($type, $commentId) : object
    {
        $comment = $type === "question" ? new CommentQ() : new CommentA();
        $comment->setDb($this->di->get("dbqb"));
        $comment->find("id", $commentId);
        return $comment;
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
        $comment->find("id", $this->form->value("id"));
        $comment->delete();
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
