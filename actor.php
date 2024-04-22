<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actor Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./images/batmovies.png">
    <style>
        .actor-details {
            text-align: center;
            margin-top: 50px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: flex-start;
        }
        .profile img {
            max-width: 300px;
            height: auto;
        }
        #info {
            color: white;
            margin-top: 20px;
            text-align: left;
            flex-basis: 100%;
            max-width: 400px;
        }
        #info h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        #info p {
            font-size: 16px;
            line-height: 1.6;
        }
        #movies {
            color: white;
            margin-top: 20px;
            text-align: center;
        }
        #movies h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        #movies ul {
            padding: 0;
            display: flex;
            justify-content: center;
            list-style: none;
            flex-wrap: wrap; /* Ensure the movies wrap to the next line */
        }
        #movies ul li {
            margin: 0 10px;
            text-align: center; /* Center-align each movie */
        }
        #movies ul li img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        #movies ul li a {
            text-decoration: none;
            color: white;
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

        <div class="actor-details">
            <div class="profile" id="profile"></div>
            <div class="info" id="info"></div>
            <div id="movies"></div>
        </div>

        <footer>
            <!-- Footer content -->
        </footer>
    </div>

    <script>
        let actor;

        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const actorId = urlParams.get('id');
            const apiKey = '77555ae36bb8bc2ae287fceee7f78c1c'; 

            fetch(`https://api.themoviedb.org/3/person/${actorId}?api_key=${apiKey}&language=en-US`)
                .then(response => response.json())
                .then(data => {
                    actor = data;
                    const profile = document.getElementById('profile');
                    const info = document.getElementById('info');
                    const moviesContainer = document.getElementById('movies');
                    
                    profile.innerHTML = `<img src="https://image.tmdb.org/t/p/w500/${data.profile_path}" alt="${data.name}">`;
                    info.innerHTML = `
                        <h2>${data.name}</h2>
                        <p>Born: ${data.birthday}</p>
                        <p>Place of Birth: ${data.place_of_birth}</p>
                        <p>${data.biography}</p>
                    `;

                    fetch(`https://api.themoviedb.org/3/person/${actorId}/movie_credits?api_key=${apiKey}&language=en-US`)
                        .then(response => response.json())
                        .then(creditsData => {
                            const movies = creditsData.cast.slice(0, 10); // Limit to top 10 movies
                            const moviesHTML = movies.map(movie => `<li><a href="movie.php?id=${movie.id}"><img src="https://image.tmdb.org/t/p/w500/${movie.poster_path}" alt="${movie.title}"><br>${movie.title} (${movie.release_date.slice(0, 4)})</a></li>`).join('');
                            moviesContainer.innerHTML = `<h3>Top Movies</h3><ul>${moviesHTML}</ul>`;
                        })
                        .catch(error => console.error('Error fetching movie credits:', error));
                })
                .catch(err => console.error(err));

            document.querySelector('.navbar .sign').addEventListener('click', function() {
                window.location.href = 'login.html';
            });
        });
    </script>
</body>
</html>

