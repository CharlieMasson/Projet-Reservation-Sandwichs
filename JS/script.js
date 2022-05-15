$(document).ready( function () {
    $('#table1').DataTable({
        "order": [[ 8, "desc" ]], //l'attribut "order" prend le dessus sur le order by de la commande "select", on peut vérifier en mettant en commentaire tout le js, ainsi le order by sera effectif
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
        
    });

    $('.delete').on('click',function (){
        if(confirm('Souhaitez-vous vraiment supprimer cette commande ?')){
            var idCommandeDelete = $(this).attr('id');
            $.ajax(
                {
                url: "../historique/historique.php",
                type: "POST",
            
                data: { idCommandeDelete: idCommandeDelete},
                success: function (result) {
                        location.reload();                                        
                }
            });
        }
    })

    $('#valider').on('click',function (){
        if(confirm('Souhaitez-vous vraiment modifier cette commande ?')){
            var idCommandeUpdate = $('div.modal-footer').attr('id');
            var sandwichSelected = $('select#sandwich').val();
            var boissonSelected = $('select#boisson').val();
            var dessertSelected = $('select#dessert').val();
            var chipsSelected = $('select#chips').val();
            var dateSelected = $('input#dateLivraison').val();
            $.ajax(
                {
                url: "../php/update.php",
                type: "POST",
                data: { idCommandeUpdate: idCommandeUpdate, sandwichSelected: sandwichSelected, boissonSelected: boissonSelected, dessertSelected: dessertSelected, chipsSelected: chipsSelected, dateSelected: dateSelected},
                success: function (result) {
                    location.reload();                                       
                }
            });
        }
    })

    $(document).on('click', '.update', function (){
            var idCommandeForm = $(this).attr('id');
            $('.modal-footer').attr('id', idCommandeForm);
            $.ajax(
                {
                url: "../php/update.php",
                type: "GET",
                data: { idCommandeForm: idCommandeForm},
                success: function (result) {
                    var result = JSON.parse(result);
                    var dateLivraison = (result[0]['date_heure_livraison_com']).replace(' ','T');
                    $('select#sandwich').val(result[0]['fk_sandwich_id']);
                    $('select#boisson').val(result[0]['fk_boisson_id']);
                    $('select#dessert').val(result[0]['fk_dessert_id']);
                    $('select#chips').val(result[0]['chips_com']);
                    $('input#dateLivraison').val(dateLivraison);                                   
                }

            });
    })

    $('#ajoutFiltre').on('click',function (){        
            var min = $('input#min').val();
            var max = $('input#max').val();
            var utilisateur = $('select#utilisateur').val();    
            $.ajax(
                {
                url: "../php/filtre.php",
                type: "POST",
                data: { min: min, max: max, utilisateur: utilisateur},
                success: function (result) {
                    location.reload();                                       
                }
        });
        }
    )

    $('#suppressionFiltre').on('click',function (){
        if(confirm('Souhaitez-vous vraiment supprimer ce filtre ?')){
            var idFiltreDelete = $(this).attr('data-filtre');
            console.log(idFiltreDelete);    
            $.ajax(
                {
                url: "../php/filtre.php",
                type: "POST",
            
                data: { idFiltreDelete: idFiltreDelete},
                success: function (result) {
                       // location.reload();                                        
                }
            });
        }
    })

} );



