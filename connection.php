<?php

namespace pdo_poo;

class Database
{
    public static $db;

    // implementação do padrão de projetos singleton
    private function __construct(){}

    public static function getInstance() : \PDO
    {
        try {
            if (!isset(self::$db)) {
                self::$db = new \PDO('sqlite:database.db');
                self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            }

            return self::$db;
        } catch (\PDOException $erro) {
            echo 'Erro: ' . $erro->getMessage();

        }
    }

    public  static function closeInstance()
    {
        self::$db = null;
    }
}

