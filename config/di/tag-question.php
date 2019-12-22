<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "tag-question" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\Tag\TagQuestion();
                return $obj;
            }
        ],
    ],
];
