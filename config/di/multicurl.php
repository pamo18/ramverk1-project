<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "multicurl" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\MultiCurl\MultiCurl();
                return $obj;
            }
        ],
    ],
];
