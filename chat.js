function insertLogin(login_tchat) {
	var $message = $("#message");
	$message.val($message.val() + login_tchat + '> ').focus();
}

var reloadTime = 1000;
function getMessages() { 

	// On lance la requête ajax
	$.getJSON('get-message?dateConnexion='+$("#dateConnexion").val(), function(data) {
	var currentScroll0= document.getElementById("text").scrollTop
				document.getElementById("text").scrollTop = document.getElementById("text").scrollHeight
				var maxScroll0 = document.getElementById("text").scrollTop
			
				$("#annonce").html('<span><b>'+data['annonce']+'</b></span><br /><br />');
				$("#text").html(data['messages']);
				
document.getElementById("text").scrollTop = document.getElementById("text").scrollHeight
var maxScroll = document.getElementById("text").scrollTop

var nb = maxScroll-currentScroll0

if(maxScroll==maxScroll0+nb){
document.getElementById("text").scrollTop=document.getElementById("text").scrollHeight
}

else{
document.getElementById("text").scrollTop = currentScroll0
}

	}); 
}

function postMessage() {
	// On lance la requête ajax
	// type: POST > nous envoyons le message

	// On encode le message pour faire passer les caractères spéciaux comme +
	var message = encodeURIComponent($("#message").val());
	$.ajax({
		type: "POST",
		url: "post-message",
		data: "message="+message,
		success: function(msg){
			// Si la réponse est true, tout s'est bien passé,
			// Si non, on a une erreur et on l'affiche
			if(msg == true) {
				// On vide la zone de texte
				$("#message").val('');
				$("#responsePost").slideUp("slow").html('');
			} else
				$("#responsePost").html(msg).slideDown("slow");
			// on resélectionne la zone de texte, en cas d'utilisation du bouton "Envoyer"
			$("#message").focus();
		},
		error: function(msg){
			// On alerte d'une erreur
			alert('Erreur');
		}
	});
}

// Au chargement de la page, on effectue cette fonction
$(document).ready(function() {
// actualisation des membres connectés
window.setInterval(getOnlineUsers, reloadTime);
	// On vérifie que la zone de texte existe
	// Servira pour la redirection en cas de suppression de compte
	// Pour ne pas rediriger quand on est sur la page de connexion
	if(document.getElementById('message')) {
		// actualisation des messages
		window.setInterval(getMessages, reloadTime);
	}
});

function getOnlineUsers() {
	// On lance la requête ajax
	$.getJSON('get-online', function(data) {
		// Si data['error'] renvoi 0, alors ça veut dire que personne n'est en ligne
		// ce qui n'est pas normal d'ailleurs
		if(data['error'] == '0') {		
			var online = '', i = 1, image, text;
			// On parcours le tableau inscrit dans
			// la colonne [list] du tableau JSON
			for (var id_tchat in data['list']) {
				
				// On met dans la variable text le statut en toute lettre
				// Et dans la variable image le lien de l'image
				if(data["list"][id_tchat]["status"] == 'busy') {
					text = 'Occup&eacute;';
					image = 'busy';
				} else if(data["list"][id_tchat]["status"] == 'inactive') {
					text = 'Absent';
					image = 'inactive';
				} else {
					text = 'En ligne';
					image = 'active';
				}
				// On affiche d'abord le lien pour insérer le pseudo dans la zone de texte
				online += '<a class="lien_tchat" href="#post" onclick="insertLogin(\''+data['list'][id_tchat]["login_tchat"]+'\')" title="'+text+'">';
				// Ensuite on affiche l'image
				online += '<img class="img_tchat" src="status-'+image+'.png" /> ';
				// Enfin on affiche le pseudo
				online += data['list'][id_tchat]["login_tchat"]+'</a>';
				
				// Si i vaut 1, ça veut dire qu'on a affiché un membre
				// et qu'on doit aller à la ligne			
				if(i == 1) {
					i = 0;	
					online += '<br>';
				}
				i++;		
			}
			$("#users").html(online);
		} else if(data['error'] == '1')
			$("#users").html('<span style="color:gray;">Aucun utilisateur connect&eacute;.</span>');
	});
}

function setStatus(status) {
	// On lance la requête ajax
	// type: POST > nous envoyons le nouveau statut
	$.ajax({
		type: "POST",
		url: "set-status",
		data: "status="+status.value,
		success: function(msg){
			// On affiche la réponse
			$("#statusResponse").html('<span style="color:green;">Le statut a &eacute;t&eacute; mis &agrave; jour</span>');
			setTimeout(rmResponse, 3000);
		},
		error: function(msg){
			// On affiche l'erreur dans la zone de réponse
			$("#statusResponse").html('<span style="color:orange">Erreur</span>');
			setTimeout(rmResponse, 3000);
		}
	});
}

function rmResponse() {
	$("#statusResponse").html('');
}