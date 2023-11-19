document.addEventListener("DOMContentLoaded", () => {
    const searchTermInput = document.getElementById("searchTerm");
    const searchButton = document.getElementById("searchButton");    
    const clearButton = document.getElementById("clearButton");    
    const resultsList = document.getElementById("resultsList");

    searchButton.addEventListener("click", () => {
        const searchTerm = searchTermInput.value;
        if (searchTerm) {
            fetch(`http://localhost:3001/api/search?query=${searchTerm}`)
                .then((response) => response.json())
                .then((data) => {
                    resultsList.innerHTML = ""; // Clear previous results
                    if (data.length > 0) {
                        data.forEach((item) => {
                            const searchResultHeading = document.createElement("h2");
                            searchResultHeading.textContent = "Search Results:";
                            searchResultHeading.classList.add("page_sub_heading");
                            const listItem = document.createElement("li");
                            const addButton = document.createElement("button");
                            addButton.textContent = "Add to Watchlist";
                            addButton.classList.add("addToWatchlist");
                            // addButton.id = ("addToWatchlist");
                            addButton.addEventListener("click", () => {
                                // Handle adding this stock to the watchlist
                                addToWatchlist(item.token, item.symbol, item.name, item.exchange);
                            });
                            listItem.textContent = `Symbol: ${item.symbol} - Name: ${item.name} - Token: ${item.token} - Exchange Segment: ${item.exchange}`;
                            listItem.appendChild(addButton);
                            resultsList.appendChild(listItem);
                            // console.log('Received item:', item);

                        });
                    } else {
                        const noResultsItem = document.createElement("li");
                        noResultsItem.textContent = "No matching instruments found.";
                        resultsList.appendChild(noResultsItem);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        }
    });

    function addToWatchlist(token, symbol, name, exchange) {
        // Send an AJAX request to add_to_watchlist.php
        const data = new FormData();
        data.append('token', token);
        data.append('symbol', symbol);
        data.append('name',name);
        data.append('exchange', exchange);
    
        fetch("add_to_watchlist.php", {
            method: "POST",
            body: data,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Added ${symbol} to your watchlist.`);
            } else {
                alert(`Failed to add ${symbol} to your watchlist. Error: ${data.error}`);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }


    clearButton.addEventListener("click",() => {
        var searchItems = document.querySelectorAll("#resultsList li");
        searchItems.forEach((item) =>{
            item.remove();
        })
        console.log("Clear Button was clicked")
    })
});
