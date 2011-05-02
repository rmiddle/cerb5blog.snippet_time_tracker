<?php
class Cerb5BlogSnippetTokenTimeTracker implements IContextToken {
	static function getValue($context, $context_values) {
		$total_time_all = -1;
		if($context_values['id']) {
			// Adds total time worked per ticket to the token list.
			$db = DevblocksPlatform::getDatabaseService();
		
			$sql = "SELECT sum(tte.time_actual_mins) mins ";
			$sql .= "FROM timetracking_entry tte ";
			$sql .= sprintf("WHERE tte.source_id =  %d ", $context_values['id']);
			$sql .= "AND tte.source_extension_id = 'timetracking.source.ticket' ";
			$sql .= "GROUP BY tte.source_id ";

			$rs = $db->Execute($sql) or die(__CLASS__ . '('.__LINE__.')'. ':' . $db->ErrorMsg()); 
			
			if($row = mysql_fetch_assoc($rs)) {
				$total_time_all = intval($row['mins']);			
			} else {
				$total_time_all = 0;
			}
	    	mysql_free_result($rs);
		}
		return $total_time_all;
	}
};