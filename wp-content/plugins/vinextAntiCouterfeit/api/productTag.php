<?php
class ProductTag {

	private $table_name = "wp_posts";
	private $table_wp_tags = "wp_tags";
	
	//constructor with $db as database connection
	public function __construct(){
	}
	
	public function getProductionDescriptionFromPostName($postName) {
		$query = sprintf("SELECT post_content from
                        " . $this->table_name . " 
                    WHERE 
                    post_name = '%s'", mysql_real_escape_string($postName));
		
		// prepare query
		$result = mysql_query($query);
		$post_desc = "";
		while ($row = mysql_fetch_assoc($result)) {
			$post_desc = $row['post_content'];
		}
		return $post_desc;
	}
	
	public function getPostIdFromPostName($postName) {
		$query = sprintf("SELECT ID from
                        " . $this->table_name . " 
                    WHERE 
                    post_name = '%s'", mysql_real_escape_string($postName));
		
		// prepare query
		$result = mysql_query($query);
		$post_id = "";
		while ($row = mysql_fetch_assoc($result)) {
			$post_id = $row['ID'];
		}
		return $post_id;
	}
	
	public function getProductPostnameFromTagID($tagID) {
		
		$query = sprintf("SELECT productId FROM " . $this->table_wp_tags . "
			WHERE tag='%s' ", mysql_real_escape_string($tagID));
		
		$result = mysql_query($query);
		
		if (!$result) {
			$message  = 'Invalid query : ' . mysql_error() . "\n";
			$message .= 'Query incomplete : ' . $query;
			die($message);
		}
		
		$post_name = "";
		while ($row = mysql_fetch_assoc($result)) {
			$post_name = $row['productId'];
		}
		
		return $post_name;
	}
	
}

?>