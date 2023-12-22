const produits_container = document.getElementById('produits');

fetch('https://localhost:8000/api/produitss?page=1')
    .then(response => response.json())
    .then(data =>{
        
        const produits = data['hydra:member'];
        console.log(produits)
        produits.map(produit => {
            console.log(produit);
            const produits_content = document.createElement('div');
            produits_content.innerHTML = `
                <h2>${produit.name}</h2>
                <img src="${produit.photos[0].name}" alt="">
                <p>${produit.categorie}</p>
            `
            produits_container.appendChild(produits_content);
        })
       
    }) 
    .catch(error => console.log(error));