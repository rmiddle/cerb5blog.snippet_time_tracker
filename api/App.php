<?php
class Cerb5BlogSnippetTokenTimeTracker implements IContextToken {
	static function getValue($context, $context_values) {
		$total_time_all = -1;
		if($context_values['id']) {
			// Adds total time worked per ticket to the token list.
			$db = DevblocksPlatform::getDatabaseService();
		
			$sql = "SELECT sum(tte.time_actual_mins) mins ";
			$sql .= "FROM timetracking_entry tte ";
			$sql .= "INNER JOIN context_link ON (context_link.to_context = 'cerberusweb.contexts.timetracking' ";
			$sql .= "AND context_link.to_context_id = tte.id AND context_link.from_context = 'cerberusweb.contexts.ticket') ";
			$sql .= sprintf("WHERE context_link.from_context_id =  %d ", $context_values['id']);
			$sql .= "GROUP BY context_link.from_context_id ";
            
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

class Cerb5BlogSnippetTokenTimeTrackerBillable implements IContextToken {
	static function getValue($context, $context_values) {
		$total_time_billable = -1;
		if($context_values['id']) {
			// Adds total time worked per ticket to the token list.
			$db = DevblocksPlatform::getDatabaseService();
		
			$sql = "SELECT sum(tte.time_actual_mins) mins ";
			$sql .= "FROM timetracking_entry tte ";
			$sql .= "INNER JOIN context_link ON (context_link.to_context = 'cerberusweb.contexts.timetracking' ";
			$sql .= "AND context_link.to_context_id = tte.id AND context_link.from_context = 'cerberusweb.contexts.ticket') ";
			$sql .= "INNER JOIN timetracking_activity tta ON (tta.id = tte.activity_id) ";
			$sql .= sprintf("WHERE context_link.from_context_id =  %d ", $context_values['id']);
			$sql .= "AND tta.rate != 0 ";
			$sql .= "GROUP BY context_link.from_context_id ";
            
			$rs = $db->Execute($sql) or die(__CLASS__ . '('.__LINE__.')'. ':' . $db->ErrorMsg()); 
			
			if($row = mysql_fetch_assoc($rs)) {
				$total_time_billable = intval($row['mins']);			
			} else {
				$total_time_billable = 0;
			}
	    	mysql_free_result($rs);
		}
		return $total_time_billable;
	}
};

class Cerb5BlogSnippetTokenTimeTrackerNonBillable implements IContextToken {
	static function getValue($context, $context_values) {
		$total_time_non_billable = -1;
		if($context_values['id']) {
			// Adds total time worked per ticket to the token list.
			$db = DevblocksPlatform::getDatabaseService();
		
			$sql = "SELECT sum(tte.time_actual_mins) mins ";
			$sql .= "FROM timetracking_entry tte ";
			$sql .= "INNER JOIN context_link ON (context_link.to_context = 'cerberusweb.contexts.timetracking' ";
			$sql .= "AND context_link.to_context_id = tte.id AND context_link.from_context = 'cerberusweb.contexts.ticket') ";
			$sql .= "INNER JOIN timetracking_activity tta ON (tta.id = tte.activity_id) ";
			$sql .= sprintf("WHERE context_link.from_context_id =  %d ", $context_values['id']);
			$sql .= "AND tta.rate = 0 ";
			$sql .= "GROUP BY context_link.from_context_id ";
            
			$rs = $db->Execute($sql) or die(__CLASS__ . '('.__LINE__.')'. ':' . $db->ErrorMsg()); 
			
			if($row = mysql_fetch_assoc($rs)) {
				$total_time_non_billable = intval($row['mins']);			
			} else {
				$total_time_non_billable = 0;
			}
	    	mysql_free_result($rs);
		}
		return $total_time_non_billable;
	}
};
