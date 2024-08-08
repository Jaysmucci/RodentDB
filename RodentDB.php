<?php
class RodentDB{
    private $hostname;
    private $port;
    private $database;
    private $username;
    private $password;
    private $conn;
    private static $instance = null;

    private function __construct()
    {
        // Load Configuration file from  config.ini
        $config = parse_ini_file(__DIR__. "config.ini", true);

        // using the variable from config.ini file
        $this->hostname = $config['database']['hostname'];
        $this->port = $config['database']['port'];
        $this->database = $config['database']['database'];
        $this->username = $config['database']['username'];
        $this->password = $config['database']['password'];

        // instantiate method connect
        $this->connect();
    }

    // Signleton pattern to ensure a single instance
    public static function getInstance(){
        if (self::$instance == null) {
            self::$instance = new RodentDB();
        }
        // return instance
        return self::$instance;
    }

    // Method to establish connection
    public function connect(){
        $this->conn = null;

        try {

            $dsn = "mysql:host={$this->hostname};port={$this->port};dbname={$this->database}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            // Here's the method to throw Error is instantiated
            $this->error($e);
        }
    }

    // Method to throw Error 
    public function error(PDOException $e){
        echo "Failed to connect" . $e->getMessage();
    }

    // Method to execute a query
    public function query(string $query){
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Method to insert into the database
    public function insert($table, $data){

        // check if data is an array
        if (!is_array($data)) {
            throw new InvalidArgumentException($data);
        }

        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), "?"));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        try {

            $stmt = $this->conn->prepare($sql);
            $success = $stmt->execute(array_values($data));

            if ($success) {
                return $this->conn->lastInsertID(); // Return the last Inserted ID if successful
            }else {
                return false; //return false if insertion fails
            }

        } catch (PDOException $e) {
            echo "Insertion Failed" . $e->getMessage(); // improve error handling
            return false;
        }
    }

    // Method to select a Query
    public function select($table, $conditions = [], $columns = "*"){
        $sql = "SELECT $columns FROM $table";

        // check if conditions is empty
        if(!empty($conditions)){
            $conditionsClause = [];
            foreach ($conditions as $column => $value) {
                $conditionsClause[] = "$column = ?";
            }
            $sql .= " WHERE " . implode(" AND ", $conditionsClause);
        }
        try {

            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array_values($conditions));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo 'SQL Error :' . $e->getMessage() . '<br>';
            echo 'Executed: ' . $sql . '<br>';
            print_r($conditions);
        }
    }

    // Method to Update a Query
    public function update($table, $data, $conditions){
        $setClauses = [];
        foreach ($data as $column => $value) {
            $setClauses[] = "$column = ?";
        }

        $conditionClause = [];
        foreach ($conditions as $column => $value) {
            $conditionClause[] = " $column = ?";
        }

        // sql qeury to update
        $sql = "UPDATE $table SET " . implode(',', $setClauses) . " WHERE " . implode(" AND ", $conditionClause);
        $stmt = $this->conn->prepare($sql);
        return $stmt->excute(array_merge(array_values($data), array_values($conditions)));
    }

    // Method to delete a Query
    public function delete($table, $conditions){
        $conditionClause = [];
        foreach ($conditions as $column => $value) {
            $conditionClause[] = "$column = ?";
        }

        $sql = "DELETE FROM $table WHERE " . implode(" AND ", $conditionClause);
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute(array_values($conditions));
            return true; // return true on success
        } catch (PDOException $e) {
            // Handle exeception, log error, or return false indicating failure
            // Example: log error message
            error_log("Error deleting record: " . $e->getMessage());
            return false;
        }
    }

}