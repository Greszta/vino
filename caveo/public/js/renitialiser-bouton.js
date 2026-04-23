document.getElementById("clearBtn").addEventListener("click", function () {
    const input = document.getElementById("search-input");
    const form = document.getElementById("search-form");

    if (!input) {
        return;
    }

    if (!form) {
        return;
    }

    input.value = "";

    try {
        form.submit();
    } catch (e) {
        // si encore erreur arrête l'essaie
    }
});
