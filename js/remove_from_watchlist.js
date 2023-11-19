document.addEventListener("DOMContentLoaded", () => {
    const watchlistTable = document.querySelector("table");

    if (watchlistTable) {
        watchlistTable.addEventListener("click", (event) => {
            if (event.target.tagName === "BUTTON") {
                const tokenToRemove = event.target.getAttribute("data-token");
                removeItemFromWatchlist(tokenToRemove);
            }
        });
    }

    function removeItemFromWatchlist(token) {
        const data = new FormData();
        data.append("token", token);

        fetch("remove_from_watchlist.php", {
            method: "POST",
            body: data,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Reload the page to reflect the updated watchlist
                    location.reload();
                } else {
                    alert(`Failed to remove item from your watchlist. Error: ${data.error}`);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }

});
