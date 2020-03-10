<?php

namespace Xtodx\Rozetka;

class RozetkaRequest
{
    private $login, $password, $cache,$coockie;

    function __construct($login, $password, $cache, $coockie)
    {
        $this->login = $login;
        $this->password = $password;
        $this->cache = $cache;
        $this->coockie = $coockie;

    }

    function request($method = "sites", $data = [], $post = false, $repeat = false)
    {

        if (file_exists($this->cache)) {
            $token = file_get_contents($this->cache);
        } else {
            $token = "";
        }
        $url = "https://api.seller.rozetka.com.ua/" . $method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        } else {
            curl_setopt($ch, CURLOPT_URL, $url . "?" . http_build_query($data));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            [
                'Authorization: Bearer ' . $token
            ]
        );
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->coockie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $data = json_decode($server_output, 1);
        if ($data["success"] == "true") {
            return $data['content'];
        } else {
            if (in_array($data["errors"]['code'], ["4301", "5401"])) {
                $login = false;
            } else {
                return false;
            }
        }

        if ($login == false) {
            if (!$repeat && $this->login()) {
                return $this->request($method, $data, $post, true);
            }
        }
        return false;
    }

    function login()
    {
        $data = $this->request("sites", [
            "username" => $this->login,
            "password" => base64_encode($this->password),
        ], true);
        if ($data['access_token']) {
            file_put_contents($this->cache, $data['access_token']);
            return true;
        } else {
            return false;
        }
    }
}