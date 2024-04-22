<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Batmovies</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./images/batmovies.png">
    <style>
        .movie {
            display: inline-block;
            margin-right: 20px;
            vertical-align: top;
            text-align: center;
        }
        .movie img {
            max-width: 150px;
            height: auto;
        }
        .movie p {
            color: white;
            margin-top: 5px;
        }
        .trending-title {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: white;
        }
        .trending-container {
            text-align: center;
        }
        .navbar a {
            color: white; /* Set link color to white */
            text-decoration: none; /* Remove underline */
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
    if(isset($_SESSION['username'])){
        echo '<div class="sign"><a href="logout.php">Logout</a></div>';
        $username = $_SESSION['username'];
        echo "<div class='sign'><a href='user.php'>$username</a></div>"; // Modified link
    } else {
        echo '<div class="sign"><a href="login.html">Sign in</a></div>';
    }

    ?>
            <div class="sign"><a href="watchlist.php">Your Watchlist</a></div>
        </nav>

        <div class="search-results">
            <h1>Search Results</h1>
            <div id="resultsContainer">
                <!-- Results will be displayed here -->
            </div>
        </div>

        <footer>
            <!-- Footer content -->
        </footer>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const query = params.get('query');
            if (query) {
                document.getElementById('searchInput').value = query;
                fetchSearchResults(query);
            }

            function fetchSearchResults(query) {
                const apiKey = '77555ae36bb8bc2ae287fceee7f78c1c'; // Replace 'YOUR_API_KEY' with your TMDB API key
                const apiUrl = `https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&query=${encodeURIComponent(query)}`;
            
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        const resultsContainer = document.getElementById('resultsContainer');
                        resultsContainer.innerHTML = ''; // Clear previous results
            
                        data.results.forEach(movie => {
                            const movieElement = document.createElement('div');
                            movieElement.classList.add('movie');
                            movieElement.innerHTML = `
                                <a href="movie.php?id=${movie.id}">
                                    <img src="https://image.tmdb.org/t/p/w500/${movie.poster_path}" alt="${movie.title}">
                                    <p>${movie.title}</p>
                                    <p>Release Date: ${movie.release_date}</p>
                                </a>
                            `;
                            resultsContainer.appendChild(movieElement);
                        });
                    })
                    .catch(error => console.error('Error fetching search results:', error));
            }
            

            document.getElementById('searchButton').addEventListener('click', function(event) {
                event.preventDefault();
                const query = document.getElementById('searchInput').value.trim();
                if (query) {
                    window.location.href = `search_results.php?query=${encodeURIComponent(query)}`;
                }
            });

            document.getElementById('searchInput').addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    const query = document.getElementById('searchInput').value.trim();
                    if (query) {
                        window.location.href = `search_results.php?query=${encodeURIComponent(query)}`;
                    }
                }
            });

            document.querySelector('.navbar .sign').addEventListener('click', function() {
                window.location.href = 'login.html';
            });
        });
    </script>
</body>
</html>
