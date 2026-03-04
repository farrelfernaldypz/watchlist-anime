<?php
class Anime {
    private $conn;
    private $table_name = "watchlist";

    public $id;
    public $judul;
    public $episode_total;
    public $episode_nonton;
    public $status;
    public $rating;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (judul, episode_total, episode_nonton, status, rating) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute([$this->judul, $this->episode_total, $this->episode_nonton, $this->status, $this->rating])) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET judul=?, episode_total=?, episode_nonton=?, status=?, rating=? 
                  WHERE id=?";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute([$this->judul, $this->episode_total, $this->episode_nonton, $this->status, $this->rating, $this->id])) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=?";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute([$this->id])) {
            return true;
        }
        return false;
    }
}
?>