// ENVOI DU FORMULAIRE AU BACKEND 
let messageForm = document.querySelector(".message_form")


messageForm.addEventListener("submit", (e)=>{
    // Annule le comportement du formulaire
    e.preventDefault()

    // Récupération des champs
    let pseudo = document.querySelector('input[name="pseudo"]')
    let message = document.querySelector('input[name="message"]')

    // Création du formulaire avec les données de pseudo et message
    let formData = new FormData()
    formData.append('pseudo', pseudo.value)
    formData.append('message', message.value)

    // Si pseudo et message sont non null
    if (pseudo, message) {
        // Envoi de la requête de type post avec le formulaire créé précédement
        fetch('./process/ajax/process_ajout_message.php', {
            method:'post', 
            body: formData
        }).then((response)=>{
            // Extraction des données JSON de la réponse
            console.log(response)
            return response.json()
        }).then((data)=>{
            console.log(data)
            // Récupération de l'élément qui a la classe chat
            let chat = document.querySelector('.chat')
            // format date 
            let date = new Date(data.date_created)
            // On fait apparaitre les information dans la page
            chat.innerHTML += `<div class="col s12 m8 offset-m2 l6 offset-l3">
            <div class="card-panel orange lighten-2">
                <div class="row valign-wrapper orange lighten-2">
                    <div class="output"></div>                                            
                    <div class="col s2" style="padding: 20px;">
                        <div id="circle" style="background-color:rgb(${Math.random() * 255},${Math.random() * 255},${Math.random() * 255});"></div>
                    </div>
                    <div class="col s10">
                        <span class="black-text">
                            <i><p class="chat-body m2" style="margin-left: 50px;">${data.pseudo} le ${date.getDate()}</p></i>
                            <h5 style="margin-left: 50px;">${data.message}</h5>
                        </span>
                    </div>
                </div>
            </div>
        </div>`
        chat.scrollTo(0, chat.scrollHeight)
        pseudo.value =''
        message.value =''
        })
    }
})

// RECUPERATION MESSAGES 


// Api javascript setInterval
// utilisation de la fonction setInterval pour exécuter une action toutes les 5 secondes
setInterval(() => {
     // envoyer une requête HTTP GET à l'URL spécifiée pour récupérer les derniers messages
    fetch('./process/ajax/process_select_message.php')
        .then((response)=>{
            return response.json()
        }).then((data)=>{
            console.log(data)
            // sélectionner l'élément de la page HTML qui est un chat
            let chat = document.querySelector('.chat')
            //vider le contenu de l'élément de chat
            chat.innerHTML = ''
            // pour chaque message récupéré
            data.forEach(message => {
                // formater la date pour qu'elle soit plus lisible
                let date = new Date(message.date_created)
                // ajouter le message à l'élément de chat
                chat.innerHTML += `<div class="col s12 m8 offset-m2 l6 offset-l3">
                <div class="card-panel orange lighten-2">
                    <div class="row valign-wrapper orange lighten-2">
                        <div class="output"></div>                                            
                        <div class="col s2" style="padding: 20px;">
                            <div id="circle" style="background-color:rgb(${Math.random() * 255},${Math.random() * 255},${Math.random() * 255});"></div>
                        </div>
                        <div class="col s10">
                            <span class="black-text">
                                <i><p class="chat-body m2" style="margin-left: 50px;">${message.pseudo} le ${date.getDate()}</p></i>
                                <h5 style="margin-left: 50px;">${message.message}</h5>
                            </span>
                        </div>
                    </div>
                </div>
            </div>`
            });
            // faire défiler automatiquement vers le bas de la page pour afficher les derniers messages
            chat.scrollTo(0, chat.scrollHeight)
        })
}, 5000);