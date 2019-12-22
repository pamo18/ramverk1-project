<?php

namespace Pamo\GameTag;

use Pamo\MultiCurl\MultiCurl;

/**
 * GameTag
 */
class GameTag
{
    /**
     * @var object $multiCurl tool.
     * @var string $baseAddress for the API.
     * @var string $apiKey for authentication.
     */
    private $multiCurl;
    private $baseAddress;
    private $apiKey;



    /**
     * GameTag a tag
     *
     * @param string $baseAddress for the API.
     * @param string $apiKey for authentication.
     *
     */
    public function init($baseAddress = null)
    {
        $this->multiCurl = new MultiCurl();

        if ($baseAddress) {
            $this->baseAddress = $baseAddress;
        } else {
            $filename = ANAX_INSTALL_PATH . "/config/api.php";
            $api =  file_exists($filename) ? require $filename : null;
            $this->baseAddress = $api ? $api["url"]["rawg"] : getenv("API_URL_RAWG");
        }
    }



    /**
     * Return the API Data
     *
     * @param string $ipAddress is the IP Address to validate.
     *
     * @return array
     */
    public function getAllData(string $params) : array
    {
        $url = ["$this->baseAddress" . "$params"];
        $header = [
            "Content-Type: application/json",
            "charset=utf-8",
            "User-Agent=GameOverflow"
        ];

        $data = $this->multiCurl->get($url, $header);
        $result = $this->build($data);

        return $result;
    }



    /**
     * Return the organized API Data
     *
     * @param array $data from the IP.
     *
     * @return array
     */
    private function build(array $data) : array
    {
        if (!$data || isset($data["results"])) {
            return null;
        }

        $data = $data[0];

        $buildData = [
            "next" => isset($data["next"]) ? $data["next"] : null,
            "previous" => isset($data["previous"]) ? $data["previous"] : null,
            "results" => []
        ];

        if ($data["results"]) {
            foreach ($data["results"] as $row) {
                $result = [
                    "name" => empty($row["name"]) ? null: $row["name"],
                    "parents" => empty($row["parent_platforms"]) ? null : $row["parent_platforms"],
                    "platforms" => empty($row["platforms"]) ? null : $row["platforms"],
                    "released" => empty($row["released"]) ? null : $row["released"],
                    "image" => empty($row["background_image"]) ? null : $row["background_image"],
                    "video" => empty($row["clip"]["clip"]) ? null : $row["clip"]["clip"],
                    "genres" => empty($row["genres"]) ? null : $row["genres"]
                ];
                array_push($buildData["results"], $result);
            }
        }
        return $buildData;
    }
}
