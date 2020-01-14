<?php

class workDB
{
    function __construct($host, $user, $password, $database)
    {
        $this->host=$host;
        $this->user=$user;
        $this->password=$password;
        $this->database=$database;
    }
    
    public function query($query)
    {
        $mysqli = new mysqli($this->host, $this->user, $this->password, $this->database);

        $result = $mysqli->query($query);

        $mysqli->close();

        return $result;
    }
    
}