<?php

class Platform
{
    /**
     * Register new device to IBM Platform
     *
     * @param $identifiant
     * @param $password
     * @return bool|string
     */
    public function registerPlatform($identifiant, $password)
    {
        // Get params
        $params = parse_ini_file('db.ini');

        // Set url with organisation and device type
        $url = "https://{$params['organisation']}.internetofthings.ibmcloud.com/api/v0002/bulk/devices/add";
        $ch = curl_init($url);

        // Set JSON data to send
        $data = array(
            "typeId" => $params['type'],
            "deviceId" => $identifiant,
            "authToken" => $password
        );
        $payload = json_encode(array($data));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Set header
        $header = array(
            "Content-Type:application/json",
            "Content-Length:" . strlen($payload)
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_USERPWD, "{$params['apiKey']}:{$params['apiToken']}");

        // Accept response and may error
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        // Send request
        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}