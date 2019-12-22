<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "comment-question" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\Comment\CommentQ();
                return $obj;
            }
        ],
    ],
];
