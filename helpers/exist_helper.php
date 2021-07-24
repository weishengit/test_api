<?php
/**
 * Check The Table For Existing Record
 * 
 * @param mysqli $conn Connection to the database
 *
 * @param string $table_name Name of the table
 * 
 * @param string $column_name Name of the column
 * 
 * @param string $value Value to check
 * 
 * @return bool return true if found or false when not found
 * 
 */
function checkExist($conn, $table_name, $column_name, $value)
{
    $sql = "SELECT $column_name FROM $table_name WHERE $column_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $value);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows() > 0) {
        return true;
    }

    $stmt->close();

    return false;
}