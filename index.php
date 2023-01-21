<?php include './partials/header.php' ?>
<?php include './utils/db_connect.php' ?>

<?php

try {
    // On récupère tous les messages et les utilisateurs
    // On les classe de la date la plus récente à la plus vielle
    $messageStmnt = $db->prepare('SELECT * FROM messages JOIN users ON users.id = messages.user_id ORDER BY messages.date_created ASC');
    // Exécution de la requête
    $messageStmnt->execute();
    // Récupération des messages et des utilisateurs
    // Retourne un tableau indexé
    $messages = $messageStmnt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Gestion des erreurs
    echo "Erreur: " . $e->getMessage();
}
?>

<main>
    <h2 style="text-align:center">Bienvenu dans le mini chat</h2>
    <div class="row" style="height:200px">
        <div class="col s12">
            <div class="container">
                <div class="chat">
                    <?php foreach ($messages as $message) { ?>
                        <div class="col s12 m8 offset-m2 l6 offset-l3">
                            <div class="card-panel orange lighten-2">
                                <div class="row valign-wrapper orange lighten-2">
                                    <div class="output"></div>                                            
                                    <div class="col s2" style="padding: 20px;">
                                        <!-- Affiche un cercle avec des couleurs aléatoires -->
                                        <div id="circle" style="background-color:rgb(<?php echo rand(0, 255);?>,<?php echo rand(0, 255);?>,<?php echo rand(0, 255);?>);"></div>
                                    </div>
                                    <div class="col s10">
                                        <span class="black-text">
                                            <!-- Affiche le nom de l'utilisateur et le message-->
                                            <i><p class="chat-body m2" style="margin-left: 50px;"><?php echo $message['pseudo'] . " le " . date("d-m-Y", strtotime($message['date_created'])) . " à " . date("G:i", strtotime($message['date_created'])) ?></p></i>
                                            <h5 style="margin-left: 50px;"><?php echo $message['message'] ?></h5>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container valign-wrapper" style="justify-content: center;padding: 35px; color: black;" id="border-form">
        <div class="valign-bottom">
            <!-- Céation du formulaire d'envoi de messages -->
            <form class="message_form" method="POST">
                <?php
                // Condition pour mettre une bordure rouge
                // sur les champs de saisie lorsqu'il y a une erreur
                if (isset($_GET['error'])) { ?>
                    <i>Entrez votre pseudo</i>
                    <input type="text" name="pseudo" style="border: 2px solid red;color:black">
                    <i>Entrez votre message</i>
                    <input type="text" name="message" style="border: 2px solid red;color:black">
                <?php
                } else { ?>
                <!-- Formulaire pour envoyer les messages -->
                    <i>Entrez votre pseudo</i>
                    <input type="text" name="pseudo">
                    <i>Entrez votre message</i>
                    <input type="text" name="message">
                    
                <?php
                } ?>
                <a name="bottom"></a>
                <input class="btn waves-effect waves-light" type="submit" value="Envoyer" style="justify-content:center">
            </form>
        </div>
    </div>
</main>

<?php require_once './partials/footer.php' ?>