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
        }
        .poster img {
            max-width: 300px;
            height: auto;
        }
        #info {
            color: white;
            margin-top: 20px;
            text-align: left;
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
        }
        #cast ul li img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        #reviews {
            color: white;
            margin-top: 20px;
            text-align: left;
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
        }
        .trailer iframe {
            max-width: 560px;
            width: 100%;
        }
        .where-to-watch {
            color: white;
            margin-top: 20px;
            text-align: left;
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

        <div class="movie-details">
            <div class="rating" id="rating">
                <input type="radio" id="star5" name="rating" value="5"><label for="star5"></label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4"></label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3"></label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2"></label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1"></label>
            </div>
            <div class="poster" id="poster"></div>
            <div class="info" id="info"></div>
            <div id="cast"></div>
            <div id="reviews"></div>
            <div class="trailer" id="trailer"></div>
            <div class="where-to-watch" id="whereToWatch"></div>
            <button id="addToWatchlist">Add to Watchlist</button>
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
                    const reviewsContainer = document.getElementById('reviews');
                    const trailerContainer = document.getElementById('trailer');
                    const whereToWatchContainer = document.getElementById('whereToWatch');
                    
                    poster.innerHTML = `<img src="https://image.tmdb.org/t/p/w500/${data.poster_path}" alt="${data.title}">`;
                    info.innerHTML = `
                        <h2>${data.title}</h2>
                        <p>Release Date: ${data.release_date}</p>
                        <p>Rating: ${data.vote_average}</p>
                        <p>${data.overview}</p>
                    `;

                    // Fetch cast details
                    fetch(`https://api.themoviedb.org/3/movie/${movieId}/credits?api_key=${apiKey}&language=en-US`)
                    .then(response => response.json())
                    .then(creditsData => {
                        const cast = creditsData.cast.slice(0, 15); // Limit to top 15 cast members
                        const castHTML = cast.map(member => `<li><img src="https://image.tmdb.org/t/p/w500/${member.profile_path}" alt="${member.name}"><br>${member.name} (${member.character})</li>`).join('');
                        castContainer.innerHTML = `<h3>Cast</h3><ul>${castHTML}</ul>`;
                    })
                    .catch(error => console.error('Error fetching cast details:', error));
                

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
                                    const providersHTML = streaming.map(provider => `<li><img src="https://image.tmdb.org/t/p/original/${provider.logo_path}" alt="${provider.provider_name}"></li>`).join('');
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
    </script>
</body>
</html>
