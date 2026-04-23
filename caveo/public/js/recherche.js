document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search-input");
    const searchForm = document.getElementById("search-form");

    let timeout;

    searchInput.addEventListener("input", function () {
        const query = searchInput.value.trim();
        const url = new URL(window.location.href);

        if (query) {
            url.searchParams.set("recherche", query);
        } else {
            url.searchParams.delete("recherche");
        }

        window.history.pushState({}, "", url);

        clearTimeout(timeout);

        timeout = setTimeout(function () {
            searchForm.submit();
        }, 2000);
    });

    searchForm.addEventListener("submit", function () {
        clearTimeout(timeout);
    });
});
