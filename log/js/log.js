$(document).ready(function() {
	$('#id_log').submit(function(e) {
		$('.status_msg').css("display", "none");
		mdp = $('#id_mdp').val();
		hash = Sha1.hash(mdp);
		$('#id_sha1mdp').val(hash);
		$('#id_mdp').val("");
		$.ajax({
			type:$(this).attr("method"),
			url:$(this).attr("action"),
			data: $(this).serialize(),
			success: function(data){
				resultat = parseInt(data);
				if (resultat) {
					redirection = '../admin/index.php';
					$(location).attr('href', redirection);
				}
				else {
					$('.status_msg').css("display", "block");
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				ecrire_erreur(99, xhr.status+" "+thrownError);
			}
		});

		e.preventDefault();
		return false;
	});
});