document.addEventListener("DOMContentLoaded", () => {
    // On récupère le bouton qui ouvre le panneau de filtres
    const openBtn = document.getElementById("openFilters");

    // On récupère le bouton qui ferme le panneau de filtres
    const closeBtn = document.getElementById("closeFilters");

    // On récupère le panneau de filtres lui-même
    const panel = document.getElementById("filterPanel");

    // On récupère l'overlay
    const overlay = document.getElementById("overlay");

    // Si l'un des éléments n'existe pas, on arrête l'exécution
    if (!openBtn || !closeBtn || !panel || !overlay) return;

    // Quand on clique sur le bouton "Filtres"
    openBtn.addEventListener("click", () => {
        // On rend le panneau visible (évite le flash au chargement)
        panel.classList.remove("hidden");

        setTimeout(() => {
            // On fait apparaître le panneau en supprimant la classe qui le déplace hors de l'écran
            panel.classList.remove("translate-y-full");
        }, 10);

        // On affiche l'overlay
        overlay.classList.remove("hidden");

        // On empêche le scroll de la page en arrière-plan
        document.body.style.overflow = "hidden";
    });

    // Fonction qui ferme le panneau et cache l'overlay
    function closeFilters() {
        // On renvoie le panneau hors de l'écran
        panel.classList.add("translate-y-full");

        setTimeout(() => {
            // On cache le panneau
            panel.classList.add("hidden");
            // On cache l'overlay
            overlay.classList.add("hidden");
        }, 300);

        // On réactive le scroll de la page
        document.body.style.overflow = "";
    }

    closeBtn.addEventListener("click", closeFilters);
    overlay.addEventListener("click", closeFilters);
});
