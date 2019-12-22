<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "game" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\Game\Game();
                $obj->init($this);
                $obj->getDatabases();
                return $obj;
            }
        ],
    ],
];
