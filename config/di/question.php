<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "question" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\Question\Question();
                return $obj;
            }
        ],
    ],
];
