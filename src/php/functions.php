<?php

/*  Per visualizzare dati dopo una query

    if ($queryResult) {
        echo "<br><br>Utenti:<br>";
        // Itera attraverso ogni riga del risultato
        while ($row = mysqli_fetch_assoc($queryResult)) {
            // Stampa i valori delle colonne
            foreach ($row as $key => $value) {
                echo $key . ': ' . $value . '<br>';
            }
            echo '<br>';
        }
    } else {
        echo "La query non ha restituito risultati.";
    }*/

namespace functions;
class functions{
    private const HOST_DB = "localhost";
    private const USERNAME = "root";
    private const PASSWORD = "";
    private const DATABASE_NAME = "saudade";

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

    public function closeConnection(){
        mysqli_close($this ->connection);
    }

    public function executeQuery($query){
        $this->openDBConnection();
        $risultato = mysqli_query($this -> connection, $query) or die("Errore di connessione" .mysqli_error($this->connection));
        $this->closeConnection();

        return $risultato;
    }
}