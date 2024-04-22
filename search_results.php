<?php
session_start();
?>
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
        .media {
            display: inline-block;
            margin-right: 20px;
            vertical-align: top;
            text-align: center;
        }
        .media img {
            max-width: 150px;
            height: auto;
        }
        .media p {
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
        #filterBtn {
            margin-top: 20px;
        }
        .filter-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .filter-container button {
            margin-right: 10px;
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
            <div class="filter-container">
                <button id="filterMoviesBtn">Movies</button>
                <button id="filterTVShowsBtn">TV Shows</button>
                <button id="filterDirectorsBtn">Directors</button>
                <button id="filterActorsBtn">Actors</button>
            </div>
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
                const apiUrl = `https://api.themoviedb.org/3/search/multi?api_key=${apiKey}&query=${encodeURIComponent(query)}`;
            
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        const resultsContainer = document.getElementById('resultsContainer');
                        resultsContainer.innerHTML = ''; // Clear previous results
            
                        data.results.forEach(item => {
                            let mediaType, title, category, posterPath, releaseDate, link;
                            if (item.media_type === 'movie' || item.media_type === 'tv') {
                                mediaType = item.media_type === 'movie' ? 'movie' : 'tv-show';
                                title = item.media_type === 'movie' ? item.title : item.name;
                                posterPath = item.poster_path ? `https://image.tmdb.org/t/p/w500/${item.poster_path}` : './images/default_poster.jpg';
                                releaseDate = item.media_type === 'movie' ? item.release_date : item.first_air_date;
                                category = item.media_type === 'movie' ? 'Movie' : 'TV Show';
                                link = `href="${mediaType}.php?id=${item.id}"`;
                            } else if (item.media_type === 'person' && item.known_for_department === 'Directing') {
                                mediaType = 'director';
                                title = item.name;
                                posterPath = item.profile_path ? `https://image.tmdb.org/t/p/w500/${item.profile_path}` : './images/default_profile.jpg';
                                category = 'Director';
                                link = `href="${mediaType}.php?id=${item.id}"`;
                            } else if (item.media_type === 'person' && item.known_for_department === 'Acting') {
                                mediaType = 'actor';
                                title = item.name;
                                posterPath = item.profile_path ? `https://image.tmdb.org/t/p/w500/${item.profile_path}` : './images/default_profile.jpg';
                                category = 'Actor';
                                link = `href="${mediaType}.php?id=${item.id}"`;
                            } else {
                                return; // Skip if not a movie, tv show, director, or actor
                            }
                            const html = `
                                <div class="media">
                                    <a ${link}>
                                        <img src="${posterPath}" alt="${title}">
                                        <p>${title}</p>
                                        <p>${category}</p>
                                        ${releaseDate ? `<p>Release Date: ${releaseDate}</p>` : ''}
                                    </a>
                                </div>
                            `;
                            resultsContainer.innerHTML += html;
                        });
                    })
                    .catch(error => console.error('Error fetching search results:', error));
            }
            
            document.getElementById('filterMoviesBtn').addEventListener('click', () => {
                filterResults('movie');
            });

            document.getElementById('filterTVShowsBtn').addEventListener('click', () => {
                filterResults('tv-show');
            });

            document.getElementById('filterDirectorsBtn').addEventListener('click', () => {
                filterResults('director');
            });

            document.getElementById('filterActorsBtn').addEventListener('click', () => {
                filterResults('actor');
            });

            function filterResults(filter) {
                const query = document.getElementById('searchInput').value.trim();
                if (query) {
                    fetchSearchResults(`${query}&type=${filter}`);
                }
            }

            document.getElementById('searchButton').addEventListener('click', function(event) {
                event.preventDefault();
                const query = document.getElementById('searchInput').value.trim();
                if (query) {
                    fetchSearchResults(query);
                }
            });

            document.getElementById('searchInput').addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    const query = document.getElementById('searchInput').value.trim();
                    if (query) {
                        fetchSearchResults(query);
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

