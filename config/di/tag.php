<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "tag" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\Tag\Tag();
                return $obj;
            }
        ],
    ],
];
