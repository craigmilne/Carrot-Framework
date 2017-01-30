
<div class="main">

	<h1>Welcome!</h1>

	<?php
	//exit;
	try {
		$result = @Database::select("*","sites");
		if (mysqli_num_rows($result) > 0) {
		    // output data of each row
		    while($row = mysqli_fetch_assoc($result)) {
		        echo "site: " . $row["site_id"]. ", Name: " . $row["site_name"]. ", Regex: <code>" . $row["url_regex"]. "</code><br/>";
		    }
		} else {
		    echo "0 results";
		}
	} catch (Exception $ex) {
		echo $ex->getMessage();
	}
	?>

</div>