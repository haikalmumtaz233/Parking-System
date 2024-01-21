<?php
class Parkir
{

    // Connection
    private $conn;

    // Table
    private $db_table = "log_parkir";

    // Columns
    public $id_log;
    public $slot;
    public $status;
    public $waktu;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL DATA
    public function getLogData()
    {
        $sqlQuery = "SELECT id_log, slot, status, waktu FROM " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createLogData()
    {
        $sqlQuery = "INSERT INTO
                        " . $this->db_table . "
                    SET
                        slot = :slot,
                        status = :status";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->slot = htmlspecialchars(strip_tags($this->slot));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // bind data
        $stmt->bindParam(":slot", $this->slot);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Edit Data
    // public function updateDataLog()
    // {
    //     $sqlQuery = "UPDATE
    //                     " . $this->db_table . "
    //                 SET
    //                     waktu_keluar = :CURRENT_TIMESTAMP(), 
    //                 WHERE 
    //                     id_log = :id_log";

    //     $stmt = $this->conn->prepare($sqlQuery);

    //     $this->waktu_keluar = htmlspecialchars(strip_tags($this->waktu_keluar));

    //     // bind data
    //     $stmt->bindParam(":waktu_keluar", $this->waktu_keluar);

    //     if ($stmt->execute()) {
    //         $itemCount = $stmt->rowCount();
    //         if ($itemCount > 0) {
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     }
    //     return false;
    // }

    // // DELETE
    // function deleteLogData()
    // {
    //     $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
    //     $stmt = $this->conn->prepare($sqlQuery);

    //     $this->id = htmlspecialchars(strip_tags($this->id));

    //     $stmt->bindParam(1, $this->id);

    //     if ($stmt->execute()) {
    //         return true;
    //     }
    //     return false;
    // }
}
