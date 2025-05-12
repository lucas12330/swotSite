<?php 
//? Création d'une class permétant la connection a la bdd

class Database {
    private $pdo;

    public function __construct($host, $dbname, $user, $pass){
        try {
            // Création de la connexion PDO
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            exit;
        }
    }

    //! Pour assigner une variable a PDO, donc a la db
    public function getConnection(){
        return $this->pdo;
    }
}

?>