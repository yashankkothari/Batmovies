<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist - Batmovies</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./images/batmovies.png">
    <style>
        /* Your CSS styles here */
        .movie-link {
    text-decoration: none; /* Remove underline from the link */
    color: white; /* Text color for the link */
    display: block; /* Ensure the link takes up the entire space of its container */
}

.movie-link:hover {
    opacity: 0.8; /* Reduce opacity on hover for a subtle effect */
}

.movie-poster {
    max-width: 100%; /* Ensure the image doesn't exceed its container's width */
    height: auto; /* Maintain aspect ratio */
    border-radius: 8px; /* Add rounded corners to the image */
}

.movie-title {
    margin-top: 8px; /* Add some space between the image and the title */
    font-size: 1.2em; /* Adjust the font size of the title */
}

.movie-release-date {
    margin-top: 4px; /* Add some space between the title and the release date */
    font-size: 0.9em; /* Adjust the font size of the release date */
    color: #ccc; /* Change color to a lighter shade */
}

    </style>
</head>

<body>
    <div class="prime">
        <nav class="navbar">
        <a href="Home.php"><img class="logo" src="./images/batmovies.png"></a>
            <div class="search">
                <input id="searchInput" type="text" placeholder="Search Batmovies">
                <button id="searchButton" type="submit"><img src="./images/search.svg"></button>
            </div>
            
<?php
    session_start();
    if(isset($_SESSION['username']))
    {
        echo '<div class="sign"><a href="logout.php">Logout</a></div>';
        $username = $_SESSION['username'];
        echo "<div class='sign'><a href='user.php'>$username</a></div>"; // Modified link
    } 
    else 
    {
        echo '<div class="sign"><a href="login.html">Sign in</a></div>';
    }
    ?>
            <div class="sign"><a href="watchlist.php">Your Watchlist</a></div>
        </nav>

        <div class="watchlist">
            <h1>Watchlist</h1>
            <div id="watchlistContainer">
                <!-- Watchlist movies will be displayed here -->
            </div>
        </div>

        <footer>
            <!-- Footer content -->
        </footer>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Retrieve watchlist from localStorage if available
            let watchlist = JSON.parse(localStorage.getItem('watchlist')) || [];
            console.log(watchlist);
            // Display watchlist movies

            const watchlistContainer = document.getElementById('watchlistContainer');
            watchlist.forEach(movie => {
                const movieElement = document.createElement('div');
                const movieID = (movie);
                var data;
                fetch(`https://api.themoviedb.org/3/movie/${movieID}?api_key=77555ae36bb8bc2ae287fceee7f78c1c&language=en-US`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);


                        const movieTitle = data.title;
                        const releaseDate = data.release_date;
                        const posterPath = data.poster_path;

                        movieElement.classList.add('movie');
                        movieElement.innerHTML = `
                        <table class="movie-table">
                        <tr>
                            <td class="movie-poster-cell">
                                <a href="movie.php?id=${movieID}" class="movie-link">
                                    <img src="https://image.tmdb.org/t/p/w300${posterPath}" alt="${movieTitle}" class="movie-poster">
                                </a>
                            </td>
                            <td class="movie-details-cell">
                                <a href="movie.php?id=${movieID}" class="movie-link">
                                    <h2 class="movie-title">${movieTitle}</h2>
                                    <p class="movie-release-date">${releaseDate}</p>
                                </a>
                            </td>
                        </tr>
                    </table>


                        `;
                        watchlistContainer.appendChild(movieElement);
                    })
                    .catch(error => {
                        console.error('Error fetching movie data:', error);
                    });
                console.log(data);

            });

            // Event listener for clicking the search button
            document.getElementById('searchButton').addEventListener('click', function (event) {
                event.preventDefault();
                const query = document.getElementById('searchInput').value.trim();
                if (query) {
                    window.location.href = `search_results.php?query=${encodeURIComponent(query)}`;
                }
            });

            // Event listener for pressing enter in the search input
            document.getElementById('searchInput').addEventListener('keypress', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    const query = document.getElementById('searchInput').value.trim();
                    if (query) {
                        window.location.href = `search_results.php?query=${encodeURIComponent(query)}`;
                    }
                }
            });

            document.querySelector('.navbar .sign').addEventListener('click', function () {
                window.location.href = 'login.html';
            });
        });
    </script>
</body>

</html>