<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "gravatar" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\Gravatar\Gravatar();
                $obj->init();
                return $obj;
            }
        ],
    ],
];
