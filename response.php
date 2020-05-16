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
}