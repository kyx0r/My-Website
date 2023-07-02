<?php
require( "../config.php" );
$sql_get_all = "SELECT * FROM questions_tbl ORDER BY question_points";
$sel = 0;
if(isset ($_GET['myOrder']) && $_GET['myOrder']!=""){
	switch ($_GET['myOrder'])
	{
		case 'question_points DESC':
			$sql_get_all = "SELECT * FROM questions_tbl ORDER BY question_points DESC";
			$sel = 1;
			break;
		case 'question_statement':
			$sql_get_all = "SELECT * FROM questions_tbl ORDER BY question_statement";
			$sel = 2;
			break;
		case 'question_statement DESC':
			$sql_get_all = "SELECT * FROM questions_tbl ORDER BY question_statement DESC";
			$sel = 3;
			break;
		case 'question_answer':
			$sql_get_all = "SELECT * FROM questions_tbl ORDER BY question_answer";
			$sel = 4;
			break;
		case 'question_answer DESC':
			$sql_get_all = "SELECT * FROM questions_tbl ORDER BY question_answer DESC";
			$sel = 5;
			break;
		case 'question_id':
			$sql_get_all = "SELECT * FROM questions_tbl ORDER BY question_id";
			$sel = 6;
			break;
		case 'question_id DESC':
			$sql_get_all = "SELECT * FROM questions_tbl ORDER BY question_id DESC";
			$sel = 7;
			break;
	
	}
}
$result = $makeconnection->query( $sql_get_all );
$overAllPoints = 0;
$num=0;
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Questions- Read</title>
	<link href="cms_style.css" rel="stylesheet" type="text/css">
	<script>
		function JS_delete_item( question_id ) {
			if ( confirm( 'Are you sure you want to delete this question?' ) ) {
				window.location.href = 'quiz_delete.php?id=' + question_id;
			}
		}
		function opts(id) {
			switch (id) {
				case 0:
					location.href="quiz_read.php?myOrder=question_points";
				break;
				case 1:
					location.href="quiz_read.php?myOrder=question_points DESC";
				break;
				case 2:
					location.href="quiz_read.php?myOrder=question_statement";
				break;
				case 3:
					location.href="quiz_read.php?myOrder=question_statement DESC";
				break;
				case 4:
					location.href="quiz_read.php?myOrder=question_answer";
				break;
				case 5:
					location.href="quiz_read.php?myOrder=question_answer DESC";
				break;
				case 6:
					location.href="quiz_read.php?myOrder=question_id";
				break;
				case 7:
					location.href="quiz_read.php?myOrder=question_id DESC";
				break;
			}
		}
	</script>
	<style>
		select {
			color: yellow;
			background-color: red;
			font-size: 20px;
			border-radius: 10px;
			margin-left: 10px;
		}
	</style>
</head>
<body>
	<div id="container">
	  <header>
		<h1>CMS (teacher area): View Quiz</h1>
		<p><a href="student_results.php">view student results</a></p>
	  </header>
<select name="opts" onchange="opts(this.selectedIndex);">
	<option <?php if ($sel == 0) { echo "selected='selected'"; } ?>>Order by Points low to high</option>
	<option <?php if ($sel == 1) { echo "selected='selected'"; } ?>>Order by Points high to low</option>
	<option <?php if ($sel == 2) { echo "selected='selected'"; } ?>>Order by statements Alphabetical a-z</option>
	<option <?php if ($sel == 3) { echo "selected='selected'"; } ?>>Order by statements Alphabetical z-a</option>
	<option <?php if ($sel == 4) { echo "selected='selected'"; } ?>>Order by answer, false's first</option>
	<option <?php if ($sel == 5) { echo "selected='selected'"; } ?>>Order by answer, true's first</option>
	<option <?php if ($sel == 6) { echo "selected='selected'"; } ?>>Order by question id, low to high</option>
	<option <?php if ($sel == 7) { echo "selected='selected'"; } ?>>Order by question id, high to low</option>
</select>
		<main>
		  <table width="100%" border="0">
				<tr>
					<th>#</th>
					<th>ID</th>
					<th>Statement</th>
					<th>Answer</th>
					<th>Points</th>
					<th>Modify</th>
					<th>Delete</th>
				</tr>
			<?php while ($row = $result->fetch_assoc()) { ?>
				<tr>
				<td>
						<?php $num++; echo $num ?>
					</td>	
					
					
					<td>
						<?php echo $row["question_id"]; ?>
					</td>
					<td>
						<?php echo $row["question_statement"]; ?>
					</td>
					<td>
						<?php  echo  ($row["question_answer"] == 1) ? "true" : "false" ; ?>
					</td>
					<td>
						<?php echo $row["question_points"]; ?>
					</td>
					<td><a href="quiz_modify.php?id=<?php echo $row["question_id"]; ?>"><button>Modify</button></a>
					</td>
					<td><a href="javascript:JS_delete_item(<?php echo $row['question_id']; ?>);"><button>Delete</button></a>
					</td>
				</tr>
				<?php
				$overAllPoints += $row[ "question_points" ];
				} //end while
				?>
				<tr class="tableTotals">
					<td colspan="4" align="right">
						<p style="text-align: right">The total number of points in the quiz is:</p>
						<p style="text-align: right"> Each points in the quiz is worth:</p>
					</td>
					<td colspan="3">
						<p>
							<?php echo $overAllPoints ?>
					  </p>
						<p>
							<?php echo number_format(100/$overAllPoints,2) ?>% </p>
				  </td>
			</tr>
		  </table>
			<br>
		<p>	<a href="quiz_add.php"><button>Add Question</button></a></p>
		
<p>	<a href="../index.php"><button>Back to top portal</button></a>	</p>
	  </main>
		<!--end main-->
	</div>
	<!--end container-->
</body>
</html>
