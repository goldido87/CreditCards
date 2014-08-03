<!DOCTYPE html>

<html>
<head>
    <title>Credit Cards Managment</title>
</head>

<body>
    
    <?php
    
    // Add the database connection logic
    include "dbconnect.php";
    //include "SignUtil/sign.js";

    define("ENCRYPTION_KEY", "!@#$%^&*");

    function encrypt($pure_string, $encryption_key) 
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, 
            utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
        return $encrypted_string;
    }

    
    // Populates database table fields with INSERT command
    if (isset($_POST['enter'])) 
    {    
        // Create a random pin code
        $pincode = substr(md5(microtime()), rand(0,26), 4);
        // Encrypt the pin code
        $encrypted = encrypt($pincode, ENCRYPTION_KEY);

        $sql = "INSERT INTO CreditCards (CardNumber, UserFName, UserLName, PinCode)
                VALUES ('$_POST[cardnumber]','$_POST[firstname]','$_POST[lastname]','" . $encrypted . "')";
            
        if (!mysqli_query($connection,$sql))
        {
            die('Error: ' . mysqli_error($connection));
        }  
    }
    
    // Delete listing by ID
    if (isset($_POST['deletebutton']))
    {
        $delete = $_POST['delete'];
        mysqli_query($connection,"DELETE FROM CreditCards WHERE CardNumber='$delete'");
    }
    
    /*mysqli_close($connection);*/

?>
    <!--Form fields for inserting a record-->
    <form action="index.php" method="post">
        Card Number:    <input type="text" name="cardnumber"><br>
        First Name:     <input type="text" name="firstname"><br>
        Last Name:      <input type="text" name="lastname"><br>
        <input type="submit" name="enter">
    </form>
    
    <br>
    <br>
    <!--Record deleted by entering ID-->
    
    <form action="index.php" method="post">
        <label for="id"><b>Delete Listing by card number: </b></label><br>
        <input type="text" id="textInput" name="delete">
        <input type="submit" name="deletebutton" value="Delete">
    </form>
     
    <br>
    <br>

 <?php

 // READS the Table and displays it
 if (!isset($_POST['submit']))
 {      
    // Display Table Data
    $table = mysqli_query($connection,"SELECT * FROM CreditCards");
    
    echo "<table border='1'>
    
    <tr>
    <th>ID</th>
    <th>Card Number</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Pin Code</th>
    </tr>";
    
    while ($row = mysqli_fetch_array($table))
    {
        echo "<tr>";
        echo "<td>" . $row['ID'] . "</td>";
        echo "<td>" . $row['CardNumber'] . "</td>";
        echo "<td>" . $row['UserFName'] . "</td>";
        echo "<td>" . $row['UserLName'] . "</td>";
        echo "<td>****</td>";
        echo "</tr>";
    }
    
    echo "</table>";   
}

?>
     <br>
   
   <!--Button that calls on upadate.php and views it's contents-->
    <form action="index.php" method="post">
    <input type="submit" value="Upate Record" name="updatebutton">
    </form>
    
<?php

  //Update listiing // Displays contents of update.php file
    
    if (isset($_POST['updatebutton'])){
        include "update.php";
    }
    
    //updates listing in database by ID // processes update.php file
    
     if (isset($_POST['updatelisting'])){
        $update = $_POST['update'];
    mysqli_query($connection,"UPDATE CreditCards SET CardNumber='$_POST[cardnumber]', UserFName='$_POST[firstname]', UserLName='$_POST[lastname]'
    WHERE ID='$update'");
    ?>
    
    <!--Script reloads page so updated field can be viewed-->
    
    <script type="text/javascript">
        parent.window.location.href="index.php";
    </script>
    
    <?php
    }
    

?>

</body>
</html>
