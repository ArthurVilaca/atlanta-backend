<?php 

namespace App\Response;

class Response
{
    private $messages;
    private $dataset;

    public function __construct() 
    {    }

    /**
     * Setter for options messages to response object
     * @param string $data message
     */
    public function setMessages($message)
    {
        $this->messages = $message;
    }

    /**
     * get for options messages to response object
     * @return string $data message
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Method Set for data options with object content
     * @param object $data 
     */
    public function setDataSet($data)
    {
        $this->dataSet = $data;
    }

    /**
     * Method get for data options with object content
     * @return object $dataSet
     */
    public function getDataset()
    {
        return $this->dataSet;
    }
}
?>