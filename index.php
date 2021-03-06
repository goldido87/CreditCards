<!DOCTYPE html>

<html>
<head>
    <script src="jquery-1.11.1.min.js"></script>
    <script src="sign.js"></script>
    <title>Credit cards Managment</title>
</head>

<body>   
    <?php
    
    // Set admin user name
    $admin = '01-300813672';

    // Add the database connection logic
    include "dbconnect.php";

    function encrypt()
    {
        $digit1 = substr(md5(microtime()) ,rand(0,26) , 4);
        $digit2 = substr(md5(microtime()) ,rand(0,26) , 4);
        $digit3 = substr(md5(microtime()) ,rand(0,26) , 4);
        $digit4 = substr(md5(microtime()) ,rand(0,26) , 4);

        $pin = strval(ord(strval($digit1)[0]) * ord(strval($digit1)[3]))[3] .
               strval(ord(strval($digit2)[0]) * ord(strval($digit2)[3]))[3] .
               strval(ord(strval($digit3)[0]) * ord(strval($digit3)[3]))[3] .
               strval(ord(strval($digit4)[0]) * ord(strval($digit4)[3]))[3];

        echo "<script type='text/javascript'>alert('Your ATM Code is: " . $pin . ", Please keep it!');</script>";
        
        return $digit1 . $digit2 . $digit3 . $digit4;
    }

    // Populates database table fields with INSERT command
    if (isset($_POST['enter'])) 
    {   
        if ($_POST['cardnumber'] != null)
        {
            $sql = "INSERT INTO CreditCards (CardNumber, UserFName, UserLName, PinCode)
                        VALUES ('$_POST[cardnumber]','$_POST[firstname]','$_POST[lastname]','" . encrypt() . "')";

            if (!mysqli_query($connection,$sql))
            {
                die('Error: ' . mysqli_error($connection));
            } 
        }
        else
        {
            echo "<script type='text/javascript'>alert('Please enter a card number');</script>";
        }
    }

    // Delete listing by ID
    if (isset($_POST['deletebutton']))
    {
        $delete = $_POST['delete'];
        $sql = "DELETE FROM CreditCards WHERE CardNumber='$delete'";
        mysqli_query($connection, $sql);
    }

?>

    <!--Form fields for inserting a record-->
    <form action="index.php" onsubmit="return isUserVerified('<?php echo $admin; ?>')" method="post">
        <fieldset>
        <legend><h3>Add a new card:</h3></legend>
            Card Number:    <input type="text" name="cardnumber"><br>
            First Name:     <input type="text" name="firstname"><br>
            Last Name:      <input type="text" name="lastname"><br><br>
            <input type="submit" name="enter" value="Add">   
        </fieldset>     
    </form>
    
    <br>
    <br>

    <!--Delete card record -->
    <form action="index.php" onsubmit="return isUserVerified('<?php echo $admin; ?>')" method="post">
        <fieldset>
        <legend><h3>Delete a card:</h3></legend>
            Card Number: <input type="text" id="textInput" name="delete"><br><br>
            <input type="submit" name="deletebutton" value="Delete">
        </fieldset>  
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
        echo "<td>" . $row['PinCode'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";   
}

?>
<br>
    
<?php

  //Update listiing // Displays contents of update.php file
    
    if (isset($_POST['updatebutton'])){
        include "update.php";
    }
    
    //updates listing in database by ID // processes update.php file
    
    if (isset($_POST['updatelisting']))
    {
        $update = $_POST['update'];
        $sql = "UPDATE CreditCards SET CardNumber='$_POST[cardnumber]',
            UserFName='$_POST[firstname]', UserLName='$_POST[lastname]'
            WHERE ID='$update'";
        mysqli_query($connection, $sql);
    ?>
    
    <?php
    }
    
?>

<script>
    function decrypt(str) {
        
        var res = "",
            op1,
            op2,
            tmpStr;

        if (str.length != 16) { return "wrong encrypted string"; }

        for (var i=0; i < 16; i+=4) {
            op1 = parseInt(str[i].charCodeAt(0));
            op2 = parseInt(str[i+3].charCodeAt(0));
            tmpStr = (op1 * op2).toString();
            res += tmpStr[tmpStr.length - 1];
        }

        return res;
    }
</script>

</body>
</html>
