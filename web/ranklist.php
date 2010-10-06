<?php
	$now = time ();
	if(isset( $_GET ['start'] ))
		$rank = intval ( $_GET ['start'] );
	else
	    $rank = 0;
	$file = "ranklist$rank.html";
	if (file_exists ( $file ))
		$last = filemtime ( $file );
	if ($now - $last < 10) {
		header ( "Location: $file" );
		exit ();
	} else {
		ob_start ();
		
		?>
		<?
		
		require_once ("oj-header.php");
		?>
	<title>Rank List</title>

	<?
		require_once ("./include/db_info.inc.php");
		//$rank = intval ( $_GET ['start'] );
		if ($rank < 0)
			$rank = 0;
		$sql = "SELECT `user_id`,`nick`,`solved`,`submit` FROM `users` ORDER BY `solved` DESC,reg_time  LIMIT  " . strval ( $rank ) . ",25";
		$result = mysql_query ( $sql ); //mysql_error();
		echo "<center><table width=90%>";
		echo "<tr class='toprow'>
				<td width=5% align=center><b>No.</b>
				<td width=10% align=center><b>User ID</b>
				<td width=55% align=center><b>Nick Name</b>
				<td width=10% align=center><b>Solved</b>
				<td width=10% align=center><b>Submit</b>
				<td width=10% align=center><b>Ratio</b></tr>";
		while ( $row = mysql_fetch_object ( $result ) ) {
			$rank ++;
			if ($rank % 2 == 1)
				echo "<tr class='oddrow'>";
			else
				echo "<tr class='evenrow'>";
			echo "<td align=center>" . $rank;
			echo "<td align=center><a href='userinfo.php?user=" . $row->user_id . "'>" . $row->user_id . "</a>";
			echo "<td align=center>" . htmlspecialchars ( $row->nick );
			echo "<td align=center><a href='status.php?user_id=" . $row->user_id . "&jresult=4'>" . $row->solved . "</a>";
			echo "<td align=center><a href='status.php?user_id=" . $row->user_id . "'>" . $row->submit . "</a>";
			//		echo "<td align=center>".$row->submit;
			echo "<td align=center>";
			if ($row->submit == 0)
				echo "0.000%";
			else
				echo sprintf ( "%.03lf%%", 100 * $row->solved / $row->submit );
			echo "</tr>";
		}
		echo "</table></center>";
		mysql_free_result ( $result );
		$sql = "SELECT count(*) as `mycount` FROM `users`";
		$result = mysql_query ( $sql );
		echo mysql_error ();
		$row = mysql_fetch_object ( $result );
		echo "<center>";
		for($i = 0; $i < $row->mycount; $i += 25) {
			echo "<a href=./ranklist.php?start=" . strval ( $i ) . ">";
			echo strval ( $i + 1 );
			echo "-";
			echo strval ( $i + 25 );
			echo "</a>&nbsp;";
			if ($i % 250 == 200)
				echo "<br>";
		}
		echo "</center>";
		mysql_free_result ( $result );
		?>
		
		<?
		require_once ("oj-footer.php");
		?>
		<?php
		
		$conntent = ob_get_contents ();
		$fp = fopen ( $file, "w" );
		fputs ( $fp, $conntent );
		fclose ( $fp );
	}
	
	?>
