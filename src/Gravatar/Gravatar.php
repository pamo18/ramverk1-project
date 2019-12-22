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
     * Geotag current place
     *
     * @param string $baseAddress for the API.
     * @param string $apiKey for authentication.
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
    public function get($email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array())
    {
        $url = $this->url;

        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }
}
