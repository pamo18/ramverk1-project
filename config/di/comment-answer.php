<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "comment-answer" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\Comment\CommentA();
                return $obj;
            }
        ],
    ],
];
