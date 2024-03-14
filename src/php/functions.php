<?php

namespace functions;
require_once("credenziali.php");

class Functions {
    private $connection;

    public function openDBConnection(){
        global $HOST_DB, $USERNAME, $PASSWORD, $DATABASE_NAME;

        $this->connection = mysqli_connect(
            $HOST_DB,
            $USERNAME,
            $PASSWORD,
            $DATABASE_NAME
        );
        $this->connection->set_charset("utf8");
        return mysqli_connect_errno() == 0;
    }

    public function closeConnection(){
        mysqli_close($this->connection);
    }

    public function executeQuery($query){
        if ($this->openDBConnection()) {
            $risultato = mysqli_query($this->connection, $query) or die("Errore di connessione" . mysqli_error($this->connection));
    
            // Verifica se la query non ha restituito alcuna riga
            if (mysqli_num_rows($risultato) == 0) {
                $risultato = null;
            }
        } else {
            $risultato = null;
        }

        $this->closeConnection();
        return $risultato;
    }
}
