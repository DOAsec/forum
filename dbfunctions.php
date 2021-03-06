<?php

function db_queryById($table, $cachevar, $id) {
	return db_queryByX($table, $cachevar, $id, "id");
}

function db_queryByX($table, $cachevar, $id, $x) {
	global $db;

	if (isset($cachevar[$id])) {
		$result = $cachevar[$id];
	} else {
		$qs = "SELECT * FROM `".$table."` WHERE ".$x." = ?;";
		$q = $db->prepare($qs);
		$q->execute(array($id));
		$result = $q->fetch();
	}
	
	return $result;
}

function db_queryAllByX($table, $id, $x) {
	global $db;

	$qs = "SELECT * FROM `".$table."` WHERE ".$x." = ?;";
	$q = $db->prepare($qs);
	$q->execute(array($id));
	$result = $q->fetchAll();
	
	return $result;
}

function db_queryLatestOrderByX($table, $x) {
	global $db;

	$qs = "SELECT * FROM `".$table."` ORDER BY ".$x." DESC;";
	$q = $db->prepare($qs);
	$q->execute();
	$result = $q->fetchAll();
	
	return $result;
}

function db_queryLatestOrderByXWhere($table, $x, $what, $where, $limit = 10) {
	global $db;

	$qs = "SELECT * FROM `".$table."` WHERE ".$what." = ? ORDER BY ".$x." DESC LIMIT ".$limit.";";
	$q = $db->prepare($qs);
	$q->execute(array($where));
	$result = $q->fetchAll();
	
	return $result;
}

function db_queryCount($table, $x, $id) {
	global $db;

	$qs = "SELECT count(*) FROM `".$table."` WHERE ".$x." = ?;";
	$q = $db->prepare($qs);
	$q->execute(array($id));
	$result = $q->fetch();
	
	return $result[0];
}

function db_queryCountLt($table, $x, $id, $what, $where) {
	global $db;

	$qs = "SELECT count(*) FROM `".$table."` WHERE ".$x." = ? AND ".$what." < ?;";
	$q = $db->prepare($qs);
	$q->execute(array($id, $where));
	$result = $q->fetch();
	
	return $result[0];
}

function db_getUID($username) {
	global $db;

	// I can only hope I remember to write this
	$q = $db->prepare("SELECT count(*) FROM `accounts` WHERE username = ?;");
	$q->execute(array($username));
	return $q->fetchColumn();
}

function db_registerUser($email, $username, $passwordhash, $avatar, $rankid, $groupid, $ip, $invitecodes) {
	global $db;

	$qs = "INSERT INTO `accounts` (`refid`, `email`, `username`, `password`, `avatar`, `rankid`, `groupid`, `isbanned`, `postcount`, `regip`, `regtime`, `invitecodes`)
VALUES ('0', ?, ?, ?, ?, ?, ?, 0, 0, ?, ?, ?);";
	$q = $db->prepare($qs);
	$q->execute(array($email, $username, $passwordhash, $avatar, $rankid, $groupid, $ip, time() $invitecodes));
}

function db_newPost($approved, $userid, $threadid, $body) {
	global $db;

	$qs = "INSERT INTO `posts` (`approved`, `accountid`, `threadid`, `body`, `timestamp`, `edittimestamp`, `editaccountid`)
VALUES (?, ?, ?, ?, ?, 0, ?);";
	$q = $db->prepare($qs);
	$q->execute(array($approved, $userid, $threadid, $body, time(), $userid));

	return $db->lastInsertId();
}
?>