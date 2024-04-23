<?php
session_start();
require_once "db_connection.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['username']) && isset($_POST['rating'])) {

        $movieTitle = $_POST['movie_title'];
        $username = $_SESSION['username'];
        $rating = $_POST['rating'];
        
        // Insert review into database
        $insertReviewQuery = "INSERT INTO movie_reviews (movie_title, username, rating) VALUES ('$movieTitle', '$username', '$rating')";
        if (mysqli_query($conn, $insertReviewQuery)) {
            echo "Review submitted successfully.";
        } else {
            echo "Error: " . $insertReviewQuery . "<br>" . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./images/batmovies.png">
    <style>
        .movie-details {
            text-align: center;
            margin-top: 50px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: flex-start;
        }
        .poster img {
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
        #cast {
            color: white;
            margin-top: 20px;
            text-align: center;
        }
        #cast h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        #cast ul {
            padding: 0;
            display: flex;
            justify-content: center;
            list-style: none;
            flex-wrap: wrap; /* Ensure the cast members wrap to the next line */
        }
        #cast ul li {
            margin: 0 10px;
            text-align: center; /* Center-align each cast member */
            cursor: pointer;
        }
        #cast ul li img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        #crew {
            color: white;
            margin-top: 20px;
            text-align: center;
            flex-basis: 100%;
        }
        #crew h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        #crew ul {
            padding: 0;
            display: flex;
            justify-content: center;
            list-style: none;
            flex-wrap: wrap; /* Ensure the crew members wrap to the next line */
        }
        #crew ul li {
            margin: 0 10px;
            text-align: center; /* Center-align each crew member */
            cursor: pointer;
        }
        #crew ul li img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        #reviews {
            color: white;
            margin-top: 20px;
            text-align: left;
            flex-basis: 100%;
        }
        #reviews h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        #reviews ul {
            list-style: none;
            padding: 0;
        }
        #reviews ul li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
        .rating {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .rating input[type="radio"] {
            display: none;
        }
        .rating label {
            cursor: pointer;
            font-size: 30px;
            color: #fff;
        }
        .rating label:before {
            content: "\2605";
            display: inline-block;
        }
        .rating input[type="radio"]:checked ~ label:before {
            color: #ffd700;
        }
        /* Adjusted logo size */
        .navbar .logo {
            width: auto;
            height: 40px;
        }
        .trailer {
            margin-top: 20px;
            flex-basis: 100%;
            max-width: 400px;
        }
        .trailer iframe {
            max-width: 400px;
            width: 100%;
        }
        .where-to-watch {
            color: white;
            margin-top: 20px;
            text-align: left;
            flex-basis: 100%;
        }
        .where-to-watch h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .providers {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .providers li img {
            max-height: 40px;
            cursor: pointer; /* Add cursor pointer */
        }
        .add-to-watchlist {
            background-color: #FF0000;
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
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

        <div class="movie-details">
    <div class="poster" id="poster"></div>
    <div class="info" id="info"></div>
    <div class="trailer" id="trailer"></div>
    <div id="cast"></div>
    <div id="crew"></div> <!-- Added crew section -->
    <center> <div id="reviews"></div> </center> 
    <center> <div class="where-to-watch" id="whereToWatch"></div></center> 
    <div class="rating" id="rating">
        <form method="post" action="">
            <input type="hidden" id="movie_title" name="movie_title">
            <input type="number" id="ratingInput" name="rating" min="1" max="10">
            <input type="submit" value="Submit">
        </form>
    </div>
    <a id="addToWatchlist" class="add-to-watchlist" href="watchlist.php">Add to Watchlist</a>
</div>

        <footer>
            <!-- Footer content -->
        </footer>
    </div>

    <script>
        let movie;

        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const movieId = urlParams.get('id');
            const apiKey = '77555ae36bb8bc2ae287fceee7f78c1c'; 

            fetch(`https://api.themoviedb.org/3/movie/${movieId}?api_key=${apiKey}&language=en-US`)
                .then(response => response.json())
                .then(data => {
                    movie = data;
                    const poster = document.getElementById('poster');
                    const info = document.getElementById('info');
                    const castContainer = document.getElementById('cast');
                    const crewContainer = document.getElementById('crew'); // Added crew container
                    const reviewsContainer = document.getElementById('reviews');
                    const trailerContainer = document.getElementById('trailer');
                    const whereToWatchContainer = document.getElementById('whereToWatch');
                    const movieTitleTag = document.getElementById('movie_title')
                    
                    poster.innerHTML = `<img src="https://image.tmdb.org/t/p/w500/${data.poster_path}" alt="${data.title}">`;
                    info.innerHTML = `
                        <h2>${data.title}</h2>
                        <p>Release Date: ${data.release_date}</p>
                        <p>Rating: ${data.vote_average}</p>
                        <p>${data.overview}</p>
                    `;
                    movieTitleTag.value = data.title 

                    // Fetch cast details
                    fetch(`https://api.themoviedb.org/3/movie/${movieId}/credits?api_key=${apiKey}&language=en-US`)
                    .then(response => response.json())
                    .then(creditsData => {
                        const cast = creditsData.cast.slice(0, 15); // Limit to top 15 cast members
                        const castHTML = cast.map(member => `<li onclick="redirectToActor(${member.id})"><img src="https://image.tmdb.org/t/p/w500/${member.profile_path}" alt="${member.name}"><br>${member.name} (${member.character})</li>`).join('');
                        castContainer.innerHTML = `<h3>Cast</h3><ul>${castHTML}</ul>`;
                    })
                    .catch(error => console.error('Error fetching cast details:', error));

                    // Fetch crew details (Directors, DOP, etc.)
                    fetch(`https://api.themoviedb.org/3/movie/${movieId}/credits?api_key=${apiKey}&language=en-US`)
                        .then(response => response.json())
                        .then(creditsData => {
                            const crew = creditsData.crew.filter(member => member.job === 'Director' || member.department === 'Directing' || member.job === 'Director of Photography').slice(0, 5); // Limit to top 5 crew members
                            const crewHTML = crew.map(member => `<li onclick="redirectToDirector(${member.id})"><img src="https://image.tmdb.org/t/p/w500/${member.profile_path}" alt="${member.name}"><br>${member.name} (${member.job})</li>`).join('');
                            crewContainer.innerHTML = `<h3>Crew</h3><ul>${crewHTML}</ul>`;
                        })
                        .catch(error => console.error('Error fetching crew details:', error));
                
                    fetch(`https://api.themoviedb.org/3/movie/${movieId}/reviews?api_key=${apiKey}&language=en-US`)
    .then(response => response.json())
    .then(reviewsData => {
        const reviews = reviewsData.results.slice(0, 5); // Get top 5 reviews
        const reviewsHTML = reviews.map(review => {
            // Truncate review content to two lines
            const truncatedContent = review.content.split('\n').slice(0, 2).join('\n');
            const moreButton = review.content.split('\n').length > 2 ? `<a href="#" class="read-more">Read more</a>` : '';
            return `<li><strong>${review.author}</strong>: ${truncatedContent}${moreButton}</li>`;
        }).join('');
        reviewsContainer.innerHTML = `<h3>Reviews</h3><ul>${reviewsHTML}</ul>`;

        // Add event listeners for "Read more" buttons
        const readMoreButtons = document.querySelectorAll('.read-more');
        readMoreButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const li = button.parentElement;
                const fullContent = li.textContent.split(': ')[1];
                li.innerHTML = `<strong>${reviews.author}</strong>: ${fullContent}`;
            });
        });
    })
    .catch(error => console.error('Error fetching reviews:', error));

                    // Fetch trailer
                    fetch(`https://api.themoviedb.org/3/movie/${movieId}/videos?api_key=${apiKey}&language=en-US`)
                        .then(response => response.json())
                        .then(videosData => {
                            const trailer = videosData.results.find(video => video.type === 'Trailer' && video.site === 'YouTube');
                            if (trailer) {
                                trailerContainer.innerHTML = `<h3>Trailer</h3><iframe width="560" height="315" src="https://www.youtube.com/embed/${trailer.key}" frameborder="0" allowfullscreen></iframe>`;
                            }
                        })
                        .catch(error => console.error('Error fetching trailer:', error));

                    // Fetch where to watch
                    fetch(`https://api.themoviedb.org/3/movie/${movieId}/watch/providers?api_key=${apiKey}`)
                        .then(response => response.json())
                        .then(providersData => {
                            const usProviders = providersData.results.US;
                            if (usProviders) {
                                const streaming = usProviders.flatrate;
                                if (streaming && streaming.length > 0) {
                                    const providersHTML = streaming.map(provider => `<li><img src="https://image.tmdb.org/t/p/original/${provider.logo_path}" alt="${provider.provider_name}" onclick="redirectToProvider('${provider.link}')"></li>`).join('');
                                    whereToWatchContainer.innerHTML = `<h3>Where to Watch</h3><ul class="providers">${providersHTML}</ul>`;
                                } else {
                                    whereToWatchContainer.innerHTML = `<h3>Where to Watch</h3><p>Not available for streaming.</p>`;
                                }
                            } else {
                                whereToWatchContainer.innerHTML = `<h3>Where to Watch</h3><p>Not available for streaming.</p>`;
                            }
                        })
                        .catch(error => console.error('Error fetching where to watch:', error));
                })
                .catch(err => console.error(err));

            document.getElementById('addToWatchlist').addEventListener('click', () => {
                if (movie) {
                    const watchlist = JSON.parse(localStorage.getItem('watchlist')) || [];
                    watchlist.push(movieId);
                    localStorage.setItem('watchlist', JSON.stringify(watchlist));
                    window.location.href = 'watchlist.php';
                }
            });

            document.querySelector('.navbar .sign').addEventListener('click', function() {
                window.location.href = 'login.html';
            });
        });

        function redirectToActor(actorId) {
            window.location.href = `actor.php?id=${actorId}`;
        }

        function redirectToDirector(directorId) {
            window.location.href = `director.php?id=${directorId}`;
        }

        function redirectToProvider(link) {
            if (link) {
                window.open(link, '_blank');
            }
        }
    </script>
</body>
</html>
