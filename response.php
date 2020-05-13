<?php

class Response
{
    public function sendResponse($message)
    {
        echo json_encode(array(
            'message' => $message,
        ));
    }
}