/**
 * Affiche les champs de modification du mot de passe après un clic sur
 * le bouton crayon dans le formulaire d'édition d'utilisateur (admin).
 */
document.addEventListener('DOMContentLoaded', function () {
    const bouton = document.getElementById('toggleMotDePasse');
    const champs = document.getElementById('motDePasseChamps');

    if (!bouton || !champs) {
        return;
    }

    bouton.addEventListener('click', function () {
        champs.classList.remove('hidden');
        bouton.classList.add('hidden');
        const premierChamp = document.getElementById('mot_de_passe');
        if (premierChamp) {
            premierChamp.focus();
        }
    });
});
