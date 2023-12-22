//Le conteneur HTML
const produits_parent = document.getElementById('produits-parent');
//console.log(produits_parent);
//Requète HTTP + URL des produits au format Json
fetch("https://localhost:8000/produits-json", {
    //Methode HTTP GET
    method: 'GET',
    //Contenu de l'entete
    headers: {
        'Content-type': 'application/json'
    },
})
    //La promesse
    .then(response => response.json())
    //Le tableau de produit
    .then(produits => {
        console.log(produits)
        //Boucle de parcours du tableau
        let carte_produits = produits.map((produit) =>
            //Injecter chaque element au format HTML
            `<div>
                <h2 class="text-info"> ${produit.name}</h2>
                <p>${produit.description}</p>
                <p class="text-success">Prix : ${produit.price} €</p>
                <p class="text-warning">Disponibilité : ${produit.avalable ? 'OUI' : 'NON'}</p>
            </div>`
        )
        //Ajout du contenu HTML a la <div> parente
        produits_parent.innerHTML = carte_produits.join(' ');
    })
    //Erreur en cas d'echec de la promesse (la requete HTTP)
    .catch(erreur => console.error(erreur))