document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addToListeModal");
    const form = document.getElementById("addToListeForm");
    const bouteilleInput = document.getElementById("modal_bouteille_id");
    const listeSelect = document.getElementById("modal_liste_id");
    const closeBtn = document.getElementById("closeModal");

    // Ouvrir modal
    document.querySelectorAll(".openAddToListeModal").forEach((button) => {
        button.addEventListener("click", () => {
            const bouteilleId = button.dataset.bouteilleId;

            bouteilleInput.value = bouteilleId;

            modal.classList.remove("hidden");

            // set action initiale
            updateFormAction();
        });
    });

    // Fermer modal
    closeBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    // Update action selon liste
    function updateFormAction() {
        const listeId = listeSelect.value;
        form.action = `/achat/${listeId}/bouteilles`;
    }

    listeSelect.addEventListener("change", updateFormAction);
});
