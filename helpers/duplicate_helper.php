<?php
/**
 * Check The Table For Duplicate Value
 * 
 * @param mysqli $conn Connection to the database
 *
 * @param string $table_name Name of the table
 * 
 * @param string $column_name Name of the column
 * 
 * @param string $value Value to check
 * 
 * @return bool return true if duplicate or false when not
 * 
 */
function checkUnique($conn, $table_name, $column_name, $value)
{
    $sql = "SELECT $column_name FROM $table_name WHERE name = ?";
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