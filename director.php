<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Director Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./images/batmovies.png">
    <style>
        .director-details {
            margin-top: 50px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: flex-start;
        }
        .director-info {
            color: white;
            text-align: left;
            flex-basis: 100%;
            max-width: 400px;
            margin-left: 20px;
        }
        .director-info h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .director-info p {
            font-size: 16px;
            line-height: 1.6;
        }
        .director-info img {
            max-width: 300px;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
            float: left;
            margin-right: 20px;
        }
        #movies {
            color: white;
            margin-top: 20px;
            text-align: center;
            flex-basis: 100%;
        }
        #movies h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        #movies ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        #movies ul li {
            margin: 10px;
        }
        #movies ul li a {
            text-decoration: none;
            color: white;
        }
        #movies ul li img {
            max-width: 150px;
            height: auto;
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

        <div class="director-details">
            <div class="director-info" id="directorInfo"></div>
            <div id="movies"></div>
        </div>

        <footer>
            <!-- Footer content -->
        </footer>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const directorId = urlParams.get('id');
            const apiKey = '77555ae36bb8bc2ae287fceee7f78c1c'; 

            fetch(`https://api.themoviedb.org/3/person/${directorId}?api_key=${apiKey}&language=en-US`)
                .then(response => response.json())
                .then(directorData => {
                    const directorInfo = document.getElementById('directorInfo');
                    const profilePath = directorData.profile_path ? `https://image.tmdb.org/t/p/w500/${directorData.profile_path}` : './images/default_profile.jpg';
                    directorInfo.innerHTML = `
                        <img src="${profilePath}" alt="${directorData.name}">
                        <h2>${directorData.name}</h2>
                        <p>${directorData.biography}</p>
                    `;
                })
                .catch(err => console.error(err));

            fetch(`https://api.themoviedb.org/3/person/${directorId}/movie_credits?api_key=${apiKey}&language=en-US`)
                .then(response => response.json())
                .then(movieData => {
                    const moviesContainer = document.getElementById('movies');
                    const directedMovies = movieData.crew.filter(movie => movie.department === 'Directing');
                    const moviesHTML = directedMovies.map(movie => {
                        const posterPath = movie.poster_path ? `https://image.tmdb.org/t/p/w500/${movie.poster_path}` : './images/default_poster.jpg';
                        return `<li><a href="movie.php?id=${movie.id}"><img src="${posterPath}" alt="${movie.title}"></a></li>`;
                    }).join('');
                    moviesContainer.innerHTML = `<h3>Movies Directed</h3><ul>${moviesHTML}</ul>`;
                })
                .catch(err => console.error(err));

            document.querySelector('.navbar .sign').addEventListener('click', function() {
                window.location.href = 'login.html';
            });
        });
    </script>
</body>
</html>
