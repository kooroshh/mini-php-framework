<?php namespace Main\Core;

class Request
{
    public function getMethod() : string
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    public function getUrl(): string
    {
        $url = $_SERVER["REQUEST_URI"];
        $position = strpos($url, "?");  

        if($position !== false)
            $url = substr($url, 0, $position);

        return $url;
    }

    public function isPost() : bool
    {
        return $this->getMethod() == "post";
    }

    public function isGet() : bool
    {
        return $this->getMethod() == "get";
    }

    public function all(): array
    {
        return array_map(fn($item) => htmlspecialchars($item), $_REQUEST);
    }

    public function input(string $key) : ?string
    {

        return $this->all()[$key] ?? null;
    }

}
