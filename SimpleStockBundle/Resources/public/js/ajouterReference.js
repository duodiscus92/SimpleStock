{% javascripts '@SYM16SimpleStockBundle/Resources/public/js/jquery-1.12.0.js' %}
    <script type="text/javascript" src="{{ asset_url }}"></script>
    <script type="text/javascript">
    $(document).ready(function(){
	// on construite l'url à partir de l'identifiant de la route route
        var url = "{{ path('reference_ajax') }}";
	// via le DOM on récupère les sélection courantes dans les listes Entrpot et Emplacement
        var entrepotElement = '#sym16_simplestockbundle_reference_entrepot';
        var emplacementElement = '#sym16_simplestockbundle_reference_emplacement';
	// dès que l'internaute change la selection de la liste Emplacement
	// ça crée un événement permettant activer le code qui suit
	$(entrepotElement).on('change', function(){
	    // on récupère l'id entrepot qui a été sélectionnée
            var entrepot_id = $(this).val();
            $.ajax({ 
		// on constuit l'objet XMLHttpRequest 
		type: "POST", // passage de paramètres par méthode POST (on peut aussi utiliser GET)
                url: url,     // url de la fonction qui tratera côté serveur la requête
                data: {'entrepot_id':entrepot_id}, // paramètres passé par la méthode POST ou GET
                dataType: 'JSON', //codage attendu pour la réponse du serveur (ici c'est encodé en JSON)
                timeout: 30000,
		// traitement si ça a fonctionné
                success: function (data) {
                    //réinitialise la liste des emplacements
                    $(emplacementElement).html('<option value> -- Selectionnez un emplacement -- </option>');
                    //met à jour la liste des emplacements
                    $.each(data, function(index){
                            var id = data[index].id;
                            var nom = data[index].nom;
                            $(emplacementElement).append('<option value="'+id+'">'+nom+'</option>');
                    });
                },
		// traitement si ça a pas fonctionné
                error: function(){
                    alert('Erreur, la connexion au serveur a été interrompue');
                }
            });
        });
    });
    </script> 
{% endjavascripts %} 