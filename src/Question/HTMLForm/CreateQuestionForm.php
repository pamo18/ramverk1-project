<?php

namespace Pamo\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Pamo\Question\Question;
use Pamo\Tag\Tag;
use Pamo\Tag\TagQuestion;

/**
 * Form to create an item.
 */
class CreateQuestionForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->user = $di->get("session")->get("user", null);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Details of the item",
            ],
            [
                "user" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => $this->user["username"],
                ],

                "title" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                ],

                "question" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                ],

                "tags" => [
                    "type" => "text"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Submit",
                    "callback" => [$this, "callbackSubmit"]
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
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->title = $this->form->value("title");
        $question->text = $this->form->value("question");
        $question->user = $this->form->value("user");
        $question->vote = 0;
        $question->save();


        $questionTag = explode(",", $this->form->value("tags"));

        if ($questionTag) {
            foreach ($questionTag as $formTag) {
                $this->addTag($formTag, $question->id);
            }
        } else {
            $this->addTag("General", $question->id);
        }

        $game = $this->di->get("game");
        $game->updateRank($question->user, 5);

        return true;
    }



    /**
     * Add a new tag.
     */
    public function addTag($tagName, $question)
    {
        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tagQuestion = new TagQuestion();
        $tagQuestion->setDb($this->di->get("dbqb"));
        $match = $tag->findAllWhere("name = ?", $tagName);

        if (count($match) == 0) {
            $tag->name = $tagName;
            $tag->save();
        }

        $tagQuestion->questionid = $question;
        $tagQuestion->tagname = $tagName;
        $tagQuestion->save();
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
