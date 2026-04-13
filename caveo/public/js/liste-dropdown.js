document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".toggle-liste").forEach((button) => {
        button.addEventListener("click", () => {
            const target = document.getElementById(button.dataset.target);

            target.classList.toggle("hidden");
        });
    });
});
