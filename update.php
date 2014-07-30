<?php ?>

<!--Form used to update a record in table -->
<form action="index.php" method="post">
	<label for="update">Update Listing by ID: </label>
	<input type="text" name="update"><br>
	Card Number: 			<input type="text" name="cardnumber"><br>
	Card Holder First Name: <input type="text" name="firstname"><br>
	Card Holder Last Name:  <input type="text" name="lastname">
	<input type="submit" name="updatelisting" value="Update">
</form>
