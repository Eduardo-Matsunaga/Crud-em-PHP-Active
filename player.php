<?php

use pdo_poo\Database;
require_once 'connection.php';
require_once 'app.php';

class player
{


    private $id;
    private $name;
    private $userName;
    private $email;
    private $password;
    private $dateRegister;

    public function __construct($id = null, $name = null, $userName = null, $email = null, $password = null, $dateRegister = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->dateRegister = $dateRegister;
    }

    public function setId( $id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }
    public function getUserName()
    {
        return $this->userName;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setDateRegister($dateRegister)
    {
        $this->dateRegister = $dateRegister;

    }
    public function getDateRegister()
    {
        return $this->dateRegister;
    }

    public function savePlayer()
    {
        $db = Database::getInstance();
        try {
            if ($this->id) {
                // Atualiza o jogador existente
                $stmt = $db->prepare("UPDATE players 
                                     SET name = :name, userName = :userName, email = :email, 
                                         password = :password, dateRegister = :dateRegister
                                     WHERE id = :id");

                $stmt->bindParam(':id', $this->id);
            } else {
                $stmt = $db->prepare("INSERT INTO players (name, userName, email, password, dateRegister) 
                                     VALUES (:name, :userName, :email, :password, :dateRegister)");
            }

            // Define os parâmetros
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':userName', $this->userName);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':dateRegister', $this->dateRegister);

            // Executa a query
            $stmt->execute();
            echo "Sucesso";
            echo '<a href="register.html">Voltar</a>';
        } catch (\PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        } finally{
            Database::closeInstance();
        }

    }
    public function getPlayerById( $id)
    {
        $db = Database::getInstance();
        try {
            $stmt = $db->prepare('SELECT * FROM players WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $player = new player();
                $player->setId($result['id']);
                $player->setName($result['name']);
                $player->setUserName($result['userName']);
                $player->setEmail($result['email']);
                $player->setPassword($result['password']);
                $player->setDateRegister($result['dateRegister']);
                return $player;
            }
            return null;
        } catch (\PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        } finally {
            Database::closeInstance();
        }

    }

    public function deletePlayer()
    {
        $db = Database::getInstance();
        try {
            $id = $this->getId();
            $stmt = $db->prepare("DELETE FROM players WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo "Jogador Deletado com sucesso";
            echo '<a href="register.html">Voltar</a>';
        } catch (\PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        } finally{
            Database::closeInstance();
        }

    }

    public static function listAll()
    {
        $db = Database::getInstance();
        $players = [];

        try {
            $stmt = $db->prepare('SELECT * FROM players');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $data) {
                $player = new player(
                    $data['id'],
                    $data['name'],
                    $data['userName'],
                    $data['email'],
                    $data['password'],
                    $data['dateRegister']
                );
                $players[] = $player;
            }
            echo '<a id="backToRegister" href="register.html">Voltar</a>';
        } catch (\PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        } finally {
            Database::closeInstance();
        }
        return $players;
    }
}
