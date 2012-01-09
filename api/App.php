<?php
class Cerb5BlogSnippetTokenTimeTracker implements IContextToken {
	static function getValue($context, $context_values) {
		$total_time_all = -1;
        echo "context = ";
        print_r($context);
        echo "context_values = ";
        print_r($context_values);
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