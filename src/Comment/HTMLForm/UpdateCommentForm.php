<?php

namespace Pamo\Comment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Pamo\Comment\CommentQ;
use Pamo\Comment\CommentA;

/**
 * Form to update an item.
 */
class UpdateCommentForm extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
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
                "legend" => "Update a comment",
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
                    "label" => false,
                    "value" => $comment->text
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Save",
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
        $comment->text = $this->form->value("text");
        $comment->save();
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



    /**
     * Callback what to do if the form was unsuccessfully submitted, this
     * happen when the submit callback method returns false or if validation
     * fails. This method can/should be implemented by the subclass for a
     * different behaviour.
     */
    public function callbackFail()
    {
        $this->di->get("response")->redirectSelf()->send();
    }
}
