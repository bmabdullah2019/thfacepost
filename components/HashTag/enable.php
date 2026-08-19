<?php
$db = new OssnDatabase();
$db->statement('SHOW TABLES LIKE "ossn_hashtags_trending"');
$db->execute();

$tablescript = "CREATE TABLE `ossn_hashtags_trending` (
  `hid` bigint(20) NOT NULL AUTO_INCREMENT,
  `subject_guid` bigint(20) NOT NULL,
  `hashtag` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `subject_type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
   PRIMARY KEY (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

$exists = $db->fetch(true);
if($exists === false) {
		$script         = preg_replace('/\-\-.*\n/', '', $tablescript);
		$sql_statements = preg_split('/;[\n\r]+/', $script);

		foreach($sql_statements as $statement) {
				$database  = new OssnDatabase();
				$statement = trim($statement);
				if(!empty($statement)) {
						try {
								$database->statement($statement);
								$database->execute();
						} catch (Exception $e) {
								$errors[] = $e->getMessage();
						}
				}
		}
}