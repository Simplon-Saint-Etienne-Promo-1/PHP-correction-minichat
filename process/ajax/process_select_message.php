<?php include '../../utils/db_connect.php';

$messageStmnt = $db->prepare('SELECT * FROM messages JOIN users ON users.id = messages.user_id ORDER BY messages.date_created ASC');

$messageStmnt->execute();
$messages = $messageStmnt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);
