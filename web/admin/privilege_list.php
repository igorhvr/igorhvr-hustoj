<?
require("admin-header.php");
echo "<title>Privilege List</title>"; 
echo "<center><h2>Privilege List</h2></center>";
$sql="select * FROM privilege where rightstr not like 'c%' ";
$result=mysql_query($sql) or die(mysql_error());
echo "<center><table width=90% border=1>";
echo "<tr><td>user<td>right<td>defunc</tr>";
for (;$row=mysql_fetch_object($result);){
	echo "<tr>";
	echo "<td>".$row->user_id;
	echo "<td>".$row->rightstr;
//	echo "<td>".$row->start_time;
//	echo "<td>".$row->end_time;
//	echo "<td><a href=contest_pr_change.php?cid=$row->contest_id>".($row->private=="0"?"Public->Private":"Private->Public")."</a>";
	echo "<td><a href=privilege_delete.php?uid=$row->user_id>Delete</a>";
//	echo "<td><a href=contest_edit.php?cid=$row->contest_id>Edit</a>";
//	echo "<td><a href=contest_add.php?cid=$row->contest_id>Copy</a>";
	echo "</tr>";
}
echo "</table></center>";
require("../oj-footer.php");
?>
