<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batmovies</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./images/batmovies.png">
    <style>
        /* Your CSS styles here */
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
            <img class="logo" src="./images/batmovies.png">

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
            <div class="sign"><a href="watchlist.html">Your Watchlist</a></div>
        </nav>

        <div class="Trending Movies">
            <div class="ft">
                <center> 
                <h1>Welcome To Batmovies</h1> 
                </center>
            </div>
            <div class="ft_content">
                <div class="trending-container" id="trendingMovies">
                    <!-- Trending movies will be displayed here -->
                </div>
            </div>
        </div>

        <!-- Other sections -->

        <footer>
            <!-- Footer content -->
        </footer>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const options = {
                method: 'GET',
                headers: {
                    accept: 'application/json',
                    Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3NzU1NWFlMzZiYjhiYzJhZTI4N2ZjZWVlN2Y3OGMxYyIsInN1YiI6IjY2MDcyZmFiZTYyNzE5MDEzMDBiY2M2MSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.-moBZ2rs1D-nEZU9XeMNuJ662prey2ukh9vIXP8LyS0' // Replace 'YOUR_API_KEY' with your TMDB API key
                }
            };

            fetch('https://api.themoviedb.org/3/trending/movie/day?language=en-US', options)
                .then(response => response.json())
                .then(data => {
                    const trendingMoviesContainer = document.getElementById('trendingMovies');
                    data.results.forEach(movie => {
                        const movieElement = document.createElement('div');
                        movieElement.classList.add('movie');
                        movieElement.innerHTML = `
                            <a href="movie.html?id=${movie.id}">
                                <img src="https://image.tmdb.org/t/p/w500/${movie.poster_path}" alt="${movie.title}">
                                <div class="details">
                                    <h3>${movie.title}</h3>
                                    <p>Rating: ${data.vote_average}</p>
                                    <p>Release Date: ${movie.release_date}</p>

                                </div>
                            </a>
                        `;
                        trendingMoviesContainer.appendChild(movieElement);
                    });
                })
                .catch(err => console.error(err));

            document.getElementById('searchButton').addEventListener('click', function(event) {
                event.preventDefault();
                search();
            });

            document.getElementById('searchInput').addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    search();
                }
            });

            function search() {
                const query = document.getElementById('searchInput').value.trim();
                if (query) {
                    window.location.href = `search_results.html?query=${encodeURIComponent(query)}`;
                }
            }

            document.querySelector('.navbar .sign').addEventListener('click', function() {
                window.location.href = 'login.html';
            });
        });
    </script>
</body>
</html>
