<?php

class Model
{
    // Database connection
    private $connection;

    /**
     * Model constructor.
     *
     * Set initial database connection
     */
    function __construct()
    {
        $this->connection = $this->dbConnect();
    }

    /**
     * Connect to database
     *
     * @return Exception|PDO|PDOException
     */
    public function dbConnect()
    {
        $params = parse_ini_file('db.ini');
        try {
            $db = new PDO($params['url'], $params['user'], $params['password'], array(\PDO::MYSQL_ATTR_INIT_COMMAND =>  'SET NAMES utf8'));
            return $db;
        }
        catch (PDOException $e) {
            return $e;
        }
    }

    /**
     * Register new user
     *
     * @param $identifiant
     * @param $password
     * @param $role
     * @return bool|PDOStatement
     */
    public function register($identifiant, $password, $role)
    {
        $rqt = "INSERT INTO users VALUES(:identifiant, :password, :role);";

        $stmt = $this->connection->prepare($rqt);
        $stmt->execute([
            'identifiant' => $identifiant,
            'password' => $password,
            'role' => $role
        ]);

        return $stmt;
    }

    /**
     * Check login
     *
     * @param $identifiant
     * @param $role
     * @return bool|PDOStatement
     */
    public function login($identifiant, $role)
    {
        $rqt = "SELECT password FROM users WHERE identifiant = :identifiant AND role = :role;";

        $stmt = $this->connection->prepare($rqt);
        $stmt->execute([
            'identifiant' => $identifiant,
            'role' => $role,
        ]);

        return $stmt;
    }
}