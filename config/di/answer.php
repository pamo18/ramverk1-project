<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "answer" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\Answer\Answer();
                return $obj;
            }
        ],
    ],
];
