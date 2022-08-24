<?php
session_start();
/*

$conn = mysqli_connect(
        'localhost',
        'id18819198_ababaruser',
        'bn5+}~(%Iu{MBL4q',
        'id18819198_ababardb'
    );*/
class Database
{
    private $host;
    private $db;
    private $user;
    private $password;
    private $charset;

    public function __construct()
    {
        $this->host = 'localhost';
        $this->db = 'ababardb';
        $this->user = 'root';
        $this->password = '';
        $this->charset = 'utf8mb4';
        
    }

    function connect()
    {
        try {
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $pdo = new PDO($connection, $this->user, $this->password, $options);

            return $pdo;
        } catch (PDOException $e) {
            print_r('Error connection: ' . $e->getMessage());
        }
    }
}
