<?php

    // DB connction created
    $connection = mysqli_connect("localhost", "root", "");
    
    // Connection check
    if (mysqli_connect_errno($connection))
    {
        echo "Failed to connect to database crud: " . mysqli_connect_error();
    } 
    
    // DB creation
    $sql = "CREATE DATABASE IF NOT EXISTS crud";
    if (mysqli_query($connection,$sql))    
    {
        echo "Welcome to Credit Cards managment Program"; ?> <br> <?php
    }
    else
    {
        echo "Error creating database: " . mysqli_error($connection);
    }
    
    // Select database
    $dbselect = mysqli_select_db($connection, "crud");
    if (!$dbselect) {
        die ("Can\'t use crud:" . mysqli_error($connection));
    }
 
    // Create Table
    $sql = "CREATE TABLE IF NOT EXISTS CreditCards(
        ID INT NOT NULL AUTO_INCREMENT, 
        CardNumber VARCHAR(30) NOT NULL,
        UserFName VARCHAR(30),
        UserLName VARCHAR(30),
        PinCode VARCHAR(1000) NOT NULL,
        PRIMARY KEY (ID))";
    
    if (mysqli_query($connection,$sql))
    {
        echo "Table 'CreditCards' created sucessfully"; ?> <br><br> <?php
    }
    else {
        echo "Error creating table: " . mysqli_error($connection);
    }
    
    /*mysqli_close($connection);*/

?>