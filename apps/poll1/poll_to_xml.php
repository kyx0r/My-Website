<?php
require('config.php'); 
$sql_get_all = "SELECT poll_vote FROM poll_tbl";
$get_votes = $makeconnection->query( $sql_get_all );
//start an XML structure
echo "<poll> \n";//start a root node
while ($row = $get_votes->fetch_assoc()) {//keep looping while there are rows
?>
<voter myoption="<?php echo $row['poll_vote'];?>"></voter>
<?php } ?>
</poll>
