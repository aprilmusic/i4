<?php

// Clients assisted
$results = query(
	"SELECT COUNT(*) AS res FROM "
		. "(SELECT DISTINCT ClientID FROM db_Contact "
			. "WHERE ContactTypeID IN (10,12,16,20,24,30,90,91,93,99) "
				. (isset($UserID) ? "AND UserID=$UserID " : "")
			. "UNION SELECT DISTINCT ClientID FROM dbi4_Contacts " 
			. "WHERE ContactTypeID IN (10,12,16,20,24,30,90,91,93,99) "
				. (isset($UserID) ? "AND UserAddedID=$UserID) " : "")
		. "AS tmp");
$stats["clients_assisted"] = $results[0]["res"]; 

// Clients assisted by phone
$results = query(
	"SELECT COUNT(*) AS res FROM "
		. "(SELECT DISTINCT ClientID FROM db_Contact "
			. "WHERE ContactTypeID IN (12,20,24,90,91,93) "
				. (isset($UserID) ? "AND UserID=$UserID " : "")
			. "UNION SELECT DISTINCT ClientID FROM dbi4_Contacts "
			. "WHERE ContactTypeID IN (12,20,24,90,91,93) "
				. (isset($UserID) ? "AND UserAddedID=$UserID) " : "")
		. "AS tmp");
$stats["clients_assisted_by_phone"] = $results[0]["res"]; 

// Clients assisted by voicemail
$results = query(
	"SELECT COUNT(*) AS res FROM "
		. "(SELECT DISTINCT ClientID FROM db_Contact "
			. "WHERE ContactTypeID = 10 "
				. (isset($UserID) ? "AND UserID=$UserID " : "")
			. "UNION SELECT DISTINCT ClientID FROM dbi4_Contacts "
			. "WHERE ContactTypeID = 10 "
				. (isset($UserID) ? "AND UserAddedID=$UserID) " : "")
		. "AS tmp");
$stats["clients_assisted_by_voicemail"] = $results[0]["res"];  

// Clients assisted by e-mail
$results = query(
	"SELECT COUNT(*) AS res FROM "
		. "(SELECT DISTINCT ClientID FROM db_Contact "
			. "WHERE ContactTypeID = 16 "
				. (isset($UserID) ? "AND UserID=$UserID " : "")			
			. "UNION SELECT DISTINCT ClientID FROM dbi4_Contacts "
			. "WHERE ContactTypeID = 16 "
				. (isset($UserID) ? "AND UserAddedID=$UserID) " : "")
		. "AS tmp");
$stats["clients_assisted_by_email"] = $results[0]["res"];

// Clients assisted by appointment
$results = query(
	"SELECT COUNT(*) AS res FROM "
		. "(SELECT DISTINCT ClientID FROM db_Contact "
			. "WHERE ContactTypeID = 30 "
				. (isset($UserID) ? "AND UserID=$UserID " : "") 
			. "UNION SELECT DISTINCT ClientID FROM dbi4_Contacts "
			. "WHERE ContactTypeID = 30 "
				. (isset($UserID) ? "AND UserAddedID=$UserID) " : "")
		. "AS tmp");
$stats["clients_assisted_by_appointment"] = $results[0]["res"]; 

// Clients assisted by month
$results = query(
	"SELECT COUNT(*) AS clients, month FROM "
		. "(SELECT DISTINCT ClientID, MONTH(Date) AS month FROM db_Contact "
				. (isset($UserID) ? "WHERE UserID=$UserID " : "")
			. "UNION SELECT DISTINCT ClientID, MONTH(ContactDate) AS month FROM dbi4_Contacts "
				. (isset($UserID) ? "WHERE UserAddedID=$UserID) " : "") 
		. "AS tmp GROUP BY month");
$stats["clients_by_month"] = $results; 

// Logins
$results = query(
	"SELECT Y, M, D, COUNT( * ) AS Logins, SUM( seconds ) AS seconds FROM "
		. "(SELECT YEAR( Login ) AS Y, MONTH( Login ) AS M, DAY( Login ) AS D, "
			. "TIME_TO_SEC( TIMEDIFF( LastAction, Login ) ) AS seconds FROM i3_Log "
			. "WHERE LastAction IS NOT NULL AND LastAction <>0 AND LastAction >= Login "
			. (isset($UserID) ? "AND UserID=$UserID) " : ")") 
		. "AS tmp GROUP BY Y, M, D"); 
$stats["logins"] = $results; 

$data = $stats; 
?>