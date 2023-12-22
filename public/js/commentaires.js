

    //La <div> parente du contenu du commentaire
    const comment_parent = document.getElementById('comment-parent');
     //console.log(comment_parent);
    const comment_form = document.querySelector('.comments');
//A la soumission du formulaire
comment_form.addEventListener('submit', function (e) {
    //Desactive le comportement par defaut (rechargement de la page)
    e.preventDefault();
    //Inteface formData = constructeur d'objet cle-valeur représentant les champs d'un formulaire
    const formData = new FormData(comment_form);
    //console.log(formData);
    //Stocker URL courante
    let currentUrl = window.location.href;
    // Supposons que l'URL ressemble à quelque chose comme "https://localhost:8000/details-produit/id-du-produit"
    // Utilisez une expression régulière pour extraire l'id du produit
    let match = currentUrl.match(/\/details-produit\/([^\/]+)$/);
    // Vérifiez si le match a réussi et récupérez le slug
    let id = match ? match[1] : null;
    //console.log(id);
    //<form action="https://localhost:8000/details-produit/{id}">
   if(id) {
    //Mise en place de la requète a l'aide d'une promesse
    fetch(this.action, {
        //Methode HTTP Post
        method: this.method,
        //Corp de la requète = objet formData = valeur du formulaire
        body: formData,
    })
        //La promesse retourne les valeur au format json
        .then(response => response.json())
        //Les données sont déserialisées et injectée dans le DOM
        .then(data => {
                    //console.log(data);
                    //alert("Votre message a été ajouté avec succès : ");
                    //Creer une div + son contenu et l'inserer dans le DOM à l'aide du conteneur parent
                    const new_comment = document.createElement('div');
                    new_comment.innerHTML = `
                    <h3 class="text-warning">${data.name}</h3>
                    <p>${data.content}</p>
                    `;
                    //Ajout au parent
                comment_parent.appendChild(new_comment);
                //Vider le formulaire
                comment_form.reset();
                //Refersh eventuel de la page
                window.location.reload();
               
        })
        //La promesse n'est pas tenue, on retourne une erreur
        .catch(error => console.error(error));
   }
});
