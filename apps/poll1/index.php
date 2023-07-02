<?php
require( "config.php" );
//////////// begin vote code/////////////////////////////////////////////////////
if ( isset( $_POST[ 'email' ] ) && $_POST[ 'email' ] != "" ) {
	$email = $_POST[ 'email' ];
	$firstNum = $_POST[ 'firstNum' ];
	$secondNum = $_POST[ 'secondNum' ];
	$security = $_POST[ 'security' ];
	$vote = $_POST[ 'vote' ];
	if ( $firstNum + $secondNum != $security ) {
		header( "Location: index.php?message=securityfail" );
	} else {
		$email_query = "SELECT poll_email FROM poll_tbl
WHERE poll_email='$email'  ";
		$myanswer = $makeconnection->query( $email_query );
		$count = $myanswer->num_rows;
		if ( $count > 0 ) {
			header( "Location: index.php?message=emailfound" );
		} else {
			$datetime = date_create()->format( 'Y-m-d H:i:s' );
			$insertSQL = "INSERT INTO poll_tbl
  (poll_email,poll_vote,poll_date)
  VALUES
  ('$email','$vote','$datetime')";
			$result = $makeconnection->query( $insertSQL );
			header( "Location: index.php?message=votesuccess" );
		}
	}
}
////////////end vote code//////////////
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width,initial-scale=1.0" name="viewport">
	<title>Favorite Cat Breed</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="js/poll_animate.js"></script>
	<script src="jquery-validation/dist/jquery.validate.js"></script>
	<link href="jquery-validation/basic_form_styling.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="css/poll_styles.css">
</head>
<body>
	<div id="container">
		<header>
			<h1>What's Your Favorite Cat Breed?</h1>
		</header>
		<main>
			<div id="vote">
				<!--vote form code goes here-->
				<?php
				if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] == 'votesuccess' ) {
					echo '<p>Thank you for voting in our poll! Here are the results so far:</p>';
					echo '<p><a href="index.php">home</a></p> ';
				} else {
					?>
				<h2>Vote Now!</h2>
				<form id="poll_vote" name="poll_vote" method="post" action="">
					<label>Maine Coon</label>
					<input type="radio" id="voteA" value="a" name="vote"/>
					<br>
					<label>Siamese </label>
					<input type="radio" id="voteB" value="b" name="vote"/>
					<br>
					<label>Persian </label>
					<input type="radio" id="voteA" value="c" name="vote"/>
					<br>
					<label for="vote" class="radioreq error"></label>
					<br>
					<label>Email:</label>
					<input name="email" type="email" required id="email" placeholder="name@domain.com">
					<label for="email" class="error">
						<?php
						if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] == 'emailfound' ) {
							echo "Sorry, you have already voted. One vote per user.";
						}
						?>
					</label>
					<br>
					<label>Solve:
       
          <?php 
		  $firstNum= rand(3,6);
		  $secondNum= rand(3,6);
           echo $firstNum.'+'.$secondNum.'?';
		   ?>   
		</label>
					<input name="firstNum" type="hidden" id="firstNum" value="<?php echo $firstNum?>">
					<input name="secondNum" type="hidden" id="secondNum" value="<?php echo $secondNum?>">
					<input name="security" type="text" required id="security" size="2" maxlength="2">
					<label for="security" class="error">
						<?php
						if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] == 'securityfail' ) {
							echo "Sorry, please try again.";
						}
						?>
					</label>
					<br>
					<input type="submit" name="submit" id="submit" value="Submit">
				</form>
				<script>
					$( "#poll_vote" ).validate( {
						rules: {
							vote: {
								required: true
							},
							email: {
								required: true
							},
							security: {
								required: true
							}
						},
						messages: {
							vote: "Make a choice!",
							email: {
								required: "email required",
								email: "Valid email address required."
							}
						}
					} );
				</script>
				<?php
				} ///end of of voted already success
				?>
			</div>
			<!--end div vote-->
			<?php
			if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] == 'votesuccess' ) {
				//show results only to users who voted
				?>
			<div id="results">
				<div id="optA" class="myopt">
					<div id="optAtop" class="myopttop">
					<img src="images/optAtop.jpg" width="100%">
					</div>
					<div id="optAbottom" class="myoptbottom"></div>
				</div>
				<!--end opt A-->
				<div id="optB" class="myopt">
					<div id="optBtop" class="myopttop">
						<img src="images/optBtop.jpg" width="100%">
					</div>
					<div id="optBbottom" class="myoptbottom"></div>
				</div>
				<!--end opt B-->
				<div id="optC" class="myopt">
					<div id="optCtop" class="myopttop">
							<img src="images/optCtop.jpg" width="100%">
					</div>
					<div id="optCbottom" class="myoptbottom"></div>
				</div>
				<!--end opt C-->
			</div>
			<div id="totals"></div>
			<!--end results-->
			<?php
			} //end of the if that shows results only if voted
			?>
		</main>
		<!--end main-->
	</div>
	<!--end container-->
</body>
</html>
