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
            "text" => "Overview",
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
            "text" => "Game Tag",
            "url" => "tag/info",
            "title" => "Game Tag",
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
