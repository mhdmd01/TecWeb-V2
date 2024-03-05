<?php

namespace functions;
class functions{
    private const HOST_DB = "localhost";
    private const USERNAME ="root";
    private const PASSWORD ="";
    private const DATABASE_NAME ="saudade";

    private $connection;

    public function openDBConnection(){
        $this -> connection = mysqli_connect(
            self::HOST_DB,
            
            self::USERNAME,
            self::PASSWORD,
            self::DATABASE_NAME
        );
        $this -> connection -> set_charset("utf8");
        return mysqli_connect_errno()==0;
    }
}