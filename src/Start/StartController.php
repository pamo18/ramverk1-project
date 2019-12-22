<?php

namespace Pamo\Start;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Start controller.
 */
class StartController implements ContainerInjectableInterface
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
        $this->base = "start";
        $this->title = "Overview";
        $this->game = $this->di->get("game");
        $this->page = $this->di->get("page");
    }



    /**
     * Landing page.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        $this->page = $this->di->get("page");
        $sql = "*, COUNT(tagname) AS cnt";

        $data = [
            "title" => $this->title,
            "question" => $this->game->question->findAllOrder("created DESC", 3),
            "tags" => $this->game->tagQuestion->findAllGroupOrder($sql, "tagname", "cnt DESC", 3),
            "users" => $this->game->user->findAllOrder("rank DESC", 3),
            "questionCount" => $this->game->question->count()->count,
            "tagCount" => $this->game->tag->count()->count,
            "userCount" => $this->game->user->count()->count,
            "gravatar" => $this->game->gravatar
        ];

        $this->page->add("block/nav-admin", $this->game->getNav());
        $this->page->add($this->base . "/index", $data);

        return $this->page->render();
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
