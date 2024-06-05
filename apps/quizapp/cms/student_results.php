<?php
require( "../config.php" );
$sql_get_students = "SELECT * FROM students_tbl ORDER BY student_last";
$result = $makeconnection->query( $sql_get_students );


$sum_student_results=0;
$student_count = $result->num_rows;
$cf = 0;
$cd = 0;
$cc = 0;
$cb = 0;
$ca = 0;
//echo $student_count;
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Student results Read</title>
	<link href="cms_style.css" rel="stylesheet" type="text/css">
	<style>
		.parent {
		  display: flex;
		  justify-content: center;
		  align-items: center;
		  height: 200px;
		  border: 1px solid black;
		  overflow: hidden;
		  background-color: #eee;
		}

		.box {
		  width: 100px;
		  height: 0px;
		  margin-bottom: -200px;
		  margin-left: 5vw;
		  margin-right: 5vw;
		  border-radius: 10px;
		}
		.tparent {
		  display: flex;
		  justify-content: center;
		  height: 100px;
		}
		.tbox {
		  width: 100px;
		  height: 0px;
		  margin-top: 5px;
		  margin-left: 5vw;
		  margin-right: 5vw;
		  word-wrap: break-word;
		  text-align: center;
		}
	</style>
	<script>
		function JS_delete_student( student_id ) {
			if ( confirm( 'Are you sure you want to delete this student and their quiz results?' ) ) {
				window.location.href = 'student_delete.php?id=' + student_id;
			}
		}
	</script>
</head>

<body>
	<div id="container">
	  <header>
		<h1>CMS (teacher area): View Student results, gradebook</h1>
		<p><a href="quiz_read.php">view/setup quiz</a></p>

	  </header>

		<main>

		  <table width="100%" border="0">
				<tr>
					<th>Last Name</th>
					<th>First Name</th>
					<th>email</th>
					<th>Points earned</th>
					<th>Out of</th>
					<th>Percentage</th>
					<th>Grade</th>
					<th>Date&Time</th>
					<th>Delete</th>


				</tr>

			<?php while ($row = $result->fetch_assoc()) {
			  ?>
				<tr>
				<td>
					<?php echo $row['student_last']; ?>
				</td>

					<td>
					<?php echo $row['student_first']; ?>
				</td>

				<td>
					<?php echo $row['student_email']; ?>
				</td>

				<td>
					<?php echo $row['student_points']; ?>
				</td>

				<td>
					<?php echo $row['student_outof']; ?>
				</td>

				<td>
					<?php echo $row['student_percent'].'%';
						$sum_student_results+=$row['student_percent'];
					?>
				</td>

				<td>
					<?php echo $row['student_grade']; ?>
				</td>

				<td>
					<?php echo $row['student_datetime']; ?>
				</td>

					<td>
					<a href="javascript:JS_delete_student(<?php echo $row['student_id']; ?>);"><button>Delete</button></a>
				</td>


				</tr>


				<?php
					$cf = $row['student_grade'] == 'F' ? $cf+20 : $cf;
					$cd = $row['student_grade'] == 'D' ? $cd+20 : $cd;
					$cc = $row['student_grade'] == 'C' ? $cc+20 : $cc;
					$cb = $row['student_grade'] == 'B' ? $cb+20 : $cb;
					$ca = $row['student_grade'] == 'A' ? $ca+20 : $ca;
				} //end while
				?>
 </table>

	<h2>Info chart</h2>
	<div class="parent">
	<?php
		echo "<div class=\"box\" style=\"height: {$cf}px; background-color: #0f1;\"></div>";
		echo "<div class=\"box\" style=\"height: {$cd}px; background-color: #f11;\"></div>";
		echo "<div class=\"box\" style=\"height: {$cc}px; background-color: #110;\"></div>";
		echo "<div class=\"box\" style=\"height: {$cb}px; background-color: #55f;\"></div>";
		echo "<div class=\"box\" style=\"height: {$ca}px; background-color: #af1;\"></div>";
	?>
	</div>
	<div class="tparent">
	<?php
		$cf /= 20;
		$cd /= 20;
		$cc /= 20;
		$cb /= 20;
		$ca /= 20;
		$pf = number_format($cf / $student_count * 100, 2);
		$pd = number_format($cd / $student_count * 100, 2);
		$pc = number_format($cc / $student_count * 100, 2);
		$pb = number_format($cb / $student_count * 100, 2);
		$pa = number_format($ca / $student_count * 100, 2);
		echo "<div class=\"tbox\">F: {$cf} students {$pf}% of class</div>";
		echo "<div class=\"tbox\">D: {$cd} students {$pd}% of class</div>";
		echo "<div class=\"tbox\">C: {$cc} students {$pc}% of class</div>";
		echo "<div class=\"tbox\">B: {$cb} students {$pb}% of class</div>";
		echo "<div class=\"tbox\">A: {$ca} students {$pa}% of class</div>";
	?>
	</div>

	<h2>Stats:</h2>
	<p>Total number of students who took the quiz: <?php echo $student_count; ?></p>
	<p>Average percent grade: <?php echo number_format($sum_student_results/$student_count,2);?>%</p>


		 <br>
	<a href="../index.php"><button>Back to top portal</button></a>







	  </main>
		<!--end main-->


	</div>
	<!--end container-->
</body>
</html>
