<?php
/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "game-tag" => [
            "shared" => true,
            "callback" => function () {
                $obj = new \Pamo\GameTag\GameTag();
                $obj->init();
                return $obj;
            }
        ],
    ],
];
