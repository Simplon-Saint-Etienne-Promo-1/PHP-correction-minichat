<?php include '../../utils/db_connect.php';


if (isset($_POST['message']) && !empty($_POST['message'] ))
{
    $isSuccess;
    if(isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
        
        try {
            // Test si le pseudo existe
            $userStmnt = $db->prepare('SELECT * FROM users WHERE pseudo = ?');
            $userStmnt->execute([
                $_POST['pseudo']
            ]);

            $user = $userStmnt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Une erreur est survenue : " . $e->getMessage();
        }

        if(empty($user)) {

            try {
            // SI l'utilisateur n'existe pas 
            // On le crée
                $msgStmnt = $db->prepare('INSERT INTO users(pseudo, date_created, ip_address) VALUES (?,?,?)');
                $isSuccess = $msgStmnt->execute([
                    $_POST['pseudo'],
                    date("Y-m-d H:i:s"),
                    $_SERVER['REMOTE_ADDR']
                ]);
            } catch (PDOException $e) {
                echo "Une erreur est survenue lors de l'insertion de l'utilisateur : " . $e->getMessage();
            }

            try {
                // Création du message avec l'id utilisateur
                $msgStmnt = $db->prepare('INSERT INTO messages(user_id, `message`, date_created, ip_address) VALUES (?,?,?,?)');
                $isSuccess = $msgStmnt->execute([
                    $db->lastInsertId(),
                    $_POST['message'],
                    date("Y-m-d H:i:s"),
                    $_SERVER['REMOTE_ADDR']
                ]);
                $msgStmnt = $db->prepare('SELECT * FROM messages JOIN users ON users.id = messages.user_id ORDER BY messages.date_created DESC LIMIT 1');
                $msgStmnt->execute();
                echo json_encode($msgStmnt->fetch(PDO::FETCH_ASSOC));
            } catch(PDOException $e) {
                echo "Erreur lors de l'insertion du message: " . $e->getMessage();
            }
        } else {
            // L'utilisateur existe
            // Création du message avec l'id utilisateur
            $msgStmnt = $db->prepare('INSERT INTO messages(user_id, `message`, date_created, ip_address) VALUES (?,?,?,?)');
            $isSuccess = $msgStmnt->execute([
                $user['id'],
                $_POST['message'],
                date("Y-m-d H:i:s"),
                $_SERVER['REMOTE_ADDR']
            ]);
            
            $msgStmnt = $db->prepare('SELECT * FROM messages JOIN users ON users.id = messages.user_id ORDER BY messages.date_created DESC LIMIT 1');
            $msgStmnt->execute();
            echo json_encode($msgStmnt->fetch(PDO::FETCH_ASSOC));

            // if ($isSuccess) {
            //     header('Location: ../index.php#bottom');    
            // } else {
            //     header('Location: ../index.php?#bottom');    
            // }
        }

        //
    }   
    //rediriger vers une page

} else {
    // header('Location: ../index.php?error=Le Message est vide !');    
}