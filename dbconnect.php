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
        ?> <h2><u> <?php echo "Credit cards managment"; ?> </u></h2><br> <?php
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
    
    mysqli_query($connection, $sql);

    // Create Users Table
    $sql = "CREATE TABLE IF NOT EXISTS Users(
        ID INT NOT NULL AUTO_INCREMENT, 
        UserName VARCHAR(50) NOT NULL,
        PRIMARY KEY (ID))";
    
    mysqli_query($connection,$sql)

    // Set admin user name
    /*$sql = "SELECT UserName 1 FROM users";
    // Execute query
    if ($result = mysqli_query($connection, $sql))
    {
        echo $result;
        $GLOBALS["adminUser"] = "CN=Token Signing Public Key2";
        mysqli_free_result($result); 
    }*/
    
    /*mysqli_close($connection);*/

?>