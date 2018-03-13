<?php 
namespace App\Response;

class Response
{
    private $messages;
    private $dataset;

    public function __construct() 
    {  
        $this->dataset = [];
    }

    /**
     * Set for options messages to response object
     * @param string $data message
     */
    public function setMessages($message)
    {
        $this->messages = $message;
    }

    /**
     * gGet for options messages to response object
     * @return string $data message
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Method Set for data options with object content
     * @param string $name name to index
     * @param object $data 
     */
    public function setDataSet($name, $data)
    {
        $this->dataSet[] = [$name => $data];
    }

    /**
     * Method get for data options with object content
     * @return object $dataSet
     */
    public function getDataset()
    {
        return $this->dataSet;
    }

     /**
     * Function to converte a object with options
     * @return object $data
     */
    public function toString()
    {   
        $data = [];    
        $messages = $this->getMessages();

        $data['message']['text'] = $messages;
        $data['message']['type'] = "S";
        
        if ($this->getDataset() != "")
        {
            $data['dataset'] = $this->getDataset();
        }

        return $data;        
    }
}
?>