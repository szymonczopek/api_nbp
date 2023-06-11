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
    public function addOrUpdateRecord($data) {
        try {
            if ($this->conn) {
                foreach ($data as $record) {
                    $currency = $record['currency'];
                    $code = $record['code'];
                    $mid = $record['mid'];

                    $sql = "SELECT * FROM rates WHERE code = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param("s", $code);
                    $stmt->execute();

                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $sql = "UPDATE rates SET mid = ? WHERE code = ?";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->bind_param("ss", $mid, $code);

                        if (!$stmt->execute()) {
                            echo "Error while updating: " . $stmt->error;
                        }
                    } else {
                        $sql = "INSERT INTO rates (currency, code, mid) VALUES (?, ?, ?)";
                        $stmt = $this->conn->prepare($sql);
                        $stmt->bind_param("sss", $currency, $code, $mid);

                        if (!$stmt->execute()) {
                            echo "Adding record error: " . $stmt->error;
                        }
                    }
                }
            } else {
                echo "Connection error: " . $this->conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function addPln(){
        try {
            if ($this->conn) {
                $sql = "SELECT * FROM rates WHERE code='PLN'";
                $result = $this->conn->query($sql);
                if (!$result->num_rows) {
                    $sql = "INSERT INTO rates (currency, code, mid) VALUES ('polski złoty', 'PLN', '1')";
                    $this->conn->query($sql);
                }
                else {
                    return;
                }
            }
        }catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getCodes(){
        try {
            if ($this->conn) {
                $sql = "SELECT code FROM rates";
                $result = $this->conn->query($sql);
                if ($result->num_rows > 0) {
                    $counter = 0;
                    while ($row = $result->fetch_assoc()) {
                        $response[$counter] = $row;
                        $counter++;
                    }
                    return $response;
                }
            else {
                echo "Error: " . $this->conn->error;
            }
            }
        }catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

    }

    public function getMid($code) {
        try {
            if ($this->conn) {
                $sql = "SELECT mid FROM rates WHERE code=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $code);
                $stmt->execute();

                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $response = null;
                    while ($row = $result->fetch_assoc()) {
                        $response = $row['mid'];
                    }
                    return $response;
                } else {
                    echo "No results found.";
                }
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getId($code) {
        try {
            if ($this->conn) {
                $sql = "SELECT id FROM rates WHERE code=?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $code);
                $stmt->execute();

                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $response = $row['id'];
                    }
                    return $response;
                } else {
                    echo "No results found.";
                }
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function addHistory($input, $result, $idRate1, $idRate2){
        try {
            if ($this->conn) {
                $sql = "INSERT INTO history (input, result, idRate1, idRate2) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("ddii", $input, $result, $idRate1, $idRate2);
                $stmt->execute();
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

?>
