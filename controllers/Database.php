<?php
class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->connect();
    }

    private function connect() {
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

            if ($this->conn->connect_error) {
                throw new Exception("Connection error: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function addOrUpdateRecord($data){
        try {
            if ($this->conn) {
                foreach ($data as $record) {
                    //SQL injection characters off
                    $currency = $this->conn->real_escape_string($record['currency']);
                    $code = $this->conn->real_escape_string($record['code']);
                    $mid = $this->conn->real_escape_string($record['mid']);

                    $sql = "SELECT * FROM rates WHERE code = '$code'";
                    $result = $this->conn->query($sql);

                    if ($result->num_rows > 0) {
                        $sql = "UPDATE rates SET mid = '$mid' WHERE code = '$code'";

                        if (!$this->conn->query($sql)) {
                            echo "Error while updating: " . $this->conn->error;
                        }
                    } else {
                        $sql = "INSERT INTO rates (currency, code, mid) VALUES ('$currency', '$code', '$mid')";

                        if (!$this->conn->query($sql)) {
                            echo "Add record error: " . $this->conn->error;
                        }
                    }
                }
            } else {
                echo "Connection error: " . $this->conn->error;
            }
        }catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function closeConnection() {
        $this->conn->close();
    }
}

?>
