<?
require_once ("admin-header.php");
?>
<?

require_once ("../include/db_info.inc.php");
?>
<?

require_once ("../include/problem.php");
?>
<?

// contest_id


$title = $_POST ['title'];
$time_limit = $_POST ['time_limit'];
$memory_limit = $_POST ['memory_limit'];
$description = $_POST ['description'];
$input = $_POST ['input'];
$output = $_POST ['output'];
$sample_input = $_POST ['sample_input'];
$sample_output = $_POST ['sample_output'];
$test_input = $_POST ['test_input'];
$test_output = $_POST ['test_output'];
$hint = $_POST ['hint'];
$source = $_POST ['source'];
$spj = $_POST ['spj'];
if (get_magic_quotes_gpc ()) {
	$title = stripslashes ( $title);
	$time_limit = stripslashes ( $time_limit);
	$memory_limit = stripslashes ( $memory_limit);
	$description = stripslashes ( $description);
	$input = stripslashes ( $input);
	$output = stripslashes ( $output);
	$sample_input = stripslashes ( $sample_input);
	$sample_output = stripslashes ( $sample_output);
	$test_input = stripslashes ( $test_input);
	$test_output = stripslashes ( $test_output);
	$hint = stripslashes ( $hint);
	$source = stripslashes ( $source);
	$spj = stripslashes ( $spj);
	$source = stripslashes ( $source );
}
//echo "->".$OJ_DATA."<-"; 
$pid=addproblem ( $title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $source, $spj, $OJ_DATA );
$basedir = "$OJ_DATA/$pid";
mkdir ( $basedir );
mkdata($pid,"sample.in",$sample_input,$OJ_DATA);
mkdata($pid,"sample.out",$sample_output,$OJ_DATA);
mkdata($pid,"test.in",$test_input,$OJ_DATA);
mkdata($pid,"test.out",$test_output,$OJ_DATA);

/*	*/
?>
<?

require_once ("../oj-footer.php");

?>

