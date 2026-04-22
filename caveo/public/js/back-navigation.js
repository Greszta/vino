document.addEventListener("DOMContentLoaded", () => {
    const backBtn = document.getElementById("js-back-3");

    if (backBtn) {
        backBtn.addEventListener("click", (e) => {
            e.preventDefault();
            window.history.go(-3);
        });
    }
});
