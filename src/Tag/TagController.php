<?php

namespace Pamo\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Pamo\Tag\HTMLForm\SearchForm;

/**
 * Tag controller.
 */
class TagController implements ContainerInjectableInterface
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
        $this->base = "tag";
        $this->title = "Tags";
        $this->game = $this->di->get("game");
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
        $this->page->add($this->base . "/crud/view-all", [
            "tags" => $this->game->tag->findAll(),
            "game" => $this->game
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
    public function infoAction($search = null) : object
    {
        $search = str_replace("-", " ", $search);

        if ($search) {
            $gameTag = $this->di->get("game-tag");
            $result = $gameTag->getAllData("/api/games?search=" . urlencode("$search"));
        }

        $form = new SearchForm($this->di, $search);
        $form->check();

        $this->page->add($this->base . "/game-tag", [
            "result" => isset($result) ? $result : null,
            "search" => $search,
            "form" => $form->getHTML($this->game->getFormSettings())
        ]);

        return $this->page->render([
            "title" => $this->title,
        ]);
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
