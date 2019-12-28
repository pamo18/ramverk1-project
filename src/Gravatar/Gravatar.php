<?php

namespace Pamo\Gravatar;

/**
 * Gravatar
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Gravatar
{
    /**
     * @var string $url for the Gravatar.
     */
    private $url;



    /**
     * Gravatar from email.
     *
     */
    public function init()
    {
        $this->url = 'https://www.gravatar.com/avatar/';
    }



    /**
     * Get user gravatar
     *
     * @return array
     */
    public function get($email)
    {
        $url = $this->url;

        $s = 80;
        $d = 'mp';
        $r = 'g';
        // $image = false;
        // $atts = array();

        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        return $url;
    }
}
