<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "user" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\User\User();
                return $obj;
            }
        ],
    ],
];
