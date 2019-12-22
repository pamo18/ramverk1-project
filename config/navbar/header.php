<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",

    // Here comes the menu items
    "items" => [
        [
            "text" => "Start",
            "url" => "",
            "title" => "Start here",
        ],
        [
            "text" => "Questions",
            "url" => "question",
            "title" => "Ask a question here",
        ],
        [
            "text" => "Tags",
            "url" => "tag",
            "title" => "Tags",
        ],
        [
            "text" => "Users",
            "url" => "user",
            "title" => "Users",
        ],
        [
            "text" => "About",
            "url" => "about",
            "title" => "About this website",
        ],
    ],
];