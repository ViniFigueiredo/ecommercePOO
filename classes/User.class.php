<?php
class User {
    protected $name;
    protected $email;
    protected $password;

    public function setPassword($password) {
        $this->password = $password;
    }

    public function save() {
        $db = new Database();
        $conn = $db->getConnection();

        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        
        $stmt = $conn->prepare($sql);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);

        try {
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function __construct($name, $email) {
        $this->name = $name;
        $this->email = $email;
    }

    public function getProfile() {
        return "User: {$this->name} ({$this->email})";
    }

}
