<?php
/**
 * Check The Table For New Records
 * 
 * @param mysqli $conn Connection to the database
 *
 * @param string $table_name Name of the table
 * 
 * @param string $value Last known number of recprds
 * 
 * @return bool return true if record count is different or false it's the same
 * 
 */
function checkTableForUpdate($conn, $table_name, $value)
{
    $sql = "SELECT COUNT(id) FROM $table_name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($total_rows);
    $stmt->fetch();
    if ($stmt->num_rows() == $value) {
        return false;
    }
    $stmt->close();

    return true;
}