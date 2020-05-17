<?php

class Response
{
    /**
     * Send response in JSON format
     *
     * @param $message
     */
    public function sendResponse($message)
    {
        echo json_encode(array(
            'message' => $message,
        ));
    }

    /**
     * Bad Request
     *
     * @param $message
     */
    public function badResponse($message)
    {
        header("HTTP/1.0 400 Bad Request");
        echo json_encode(array(
            'message' => $message,
        ));
    }
}