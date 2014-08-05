<!DOCTYPE html>

<html>
<head>
    <script src="sign.js"></script>
    <title>Credit Cards Managment</title>
</head>

<body>   
    <?php
    
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

    function isUserValid()
    {
        echo '<script type="text/javascript">'
            ,'signText(sign);'
            ,'</script>';

        $result = $_GET['signed'];

        if ($result == 'true')
            return true;
        return false;
    }

    // Populates database table fields with INSERT command
    if (isset($_POST['enter'])) 
    {    
        $sql = "INSERT INTO CreditCards (CardNumber, UserFName, UserLName, PinCode)
                VALUES ('$_POST[cardnumber]','$_POST[firstname]','$_POST[lastname]','" . encrypt() . "')";
            
        if (!mysqli_query($connection,$sql))
        {
            if (isUserValid())
            {
                $sql = "INSERT INTO CreditCards (CardNumber, UserFName, UserLName, PinCode)
                        VALUES ('$_POST[cardnumber]','$_POST[firstname]','$_POST[lastname]','" . encrypt() . "')";
                    
                if (!mysqli_query($connection,$sql))
                {
                    die('Error: ' . mysqli_error($connection));
                }
            }
        }  
    }
    
    // Delete listing by ID
    if (isset($_POST['deletebutton']))
    {
        if (isUserValid())
        {
            $delete = $_POST['delete'];
            mysqli_query($connection,"DELETE FROM CreditCards WHERE CardNumber='$delete'");
        }
    }

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
        // echo "<td>" . $row['PinCode'] . "</td>";
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
    
    if (isset($_POST['updatelisting']))
    {
        if (isUserValid())
        {
            $update = $_POST['update'];
            mysqli_query($connection,
                "UPDATE CreditCards SET CardNumber='$_POST[cardnumber]',
                UserFName='$_POST[firstname]', UserLName='$_POST[lastname]'
                WHERE ID='$update'");
        }
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
