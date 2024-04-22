<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batmovies</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style3.css">
    <link rel="icon" href="./images/batmovies.png">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <style>
        .carousel-title {
            font-size: 2em;
            margin-bottom: 20px;
            color: #FFFFFF;
            text-align: center;
        }
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
        .movie-item {
            text-align: center;
        }

        .movie-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .title-in {
            margin-top: 10px;
        }

        .title-in h6 {
            margin: 5px 0;
            font-size: 18px;
        }

        .title-in p {
            margin: 5px 0;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="prime">
        <nav class="navbar">
        <a href="Home.php"><img class="logo" src="./images/batmovies.png"></a>
            <div class="search">
                <input id="searchInput" type="text" placeholder="Search Batmovies For Movies, TV Shows, Cast">
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batmovies</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css" />
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="Trending Movies">
        <div class="ft">
            <center> 
                <h1>Welcome To Batmovies</h1> 

                <h3>Explore the world of cinema with Batmovies. From trending blockbusters to timeless classics, discover, watch, and enjoy the latest movies right here.</h3>
            </center>
        </div>
        <h2 class="carousel-title">Trending Movies</h2>
        <div class="movie-carousel trending-movies">
            <!-- Popular movies will be displayed here -->
        </div>
    </div>
    <div class="New Releases">
        <h2 class="carousel-title">New Releases</h2>
        <div class="movie-carousel new-releases">
            <!-- Movie items will be dynamically inserted here -->
        </div>
    </div>


<div class="TV Shows">
        <h2 class="carousel-title">TV Shows</h2>
        <div class="movie-carousel tv-shows">
            <!-- TV show items will be dynamically inserted here -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            // Trending Movies
            $('.trending-movies').slick({
                slidesToShow: 8,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 640,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

            fetch('https://api.themoviedb.org/3/movie/popular?api_key=77555ae36bb8bc2ae287fceee7f78c1c')
                .then(response => response.json())
                .then(data => {
                    const popularMoviesContainer = $('.trending-movies');
                    data.results.forEach(movie => {
                        const movieElement = `
                            <div class="movie-item">
                                <div class="mv-img">
                                    <a href="movie.php?id=${movie.id}">
                                        <img src="https://image.tmdb.org/t/p/w500/${movie.poster_path}" alt="${movie.title}">
                                    </a>
                                </div>
                                <div class="title-in">
                                    <h6><a href="movie.php?id=${movie.id}">${movie.title}</a></h6>
                                    <p><i class="ion-android-star"></i><span>${movie.vote_average}</span> /10</p>
                                </div>
                            </div>
                        `;
                        popularMoviesContainer.slick('slickAdd', movieElement);
                    });
                })
                .catch(err => console.error(err));

            // New Releases
            $('.new-releases').slick({
                slidesToShow: 8,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 640,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

            fetch('https://api.themoviedb.org/3/movie/now_playing?api_key=77555ae36bb8bc2ae287fceee7f78c1c')
                .then(response => response.json())
                .then(data => {
                    const newReleasesContainer = $('.new-releases');
    data.results.forEach(movie => {
      const movieElement = `
        <div class="movie-item">
          <a href="movie.php?id=${movie.id}">
            <img src="https://image.tmdb.org/t/p/w500/${movie.poster_path}" alt="${movie.title}">
            <div class="details">
              ${movie.genres ? movie.genres.map(genre => `<span>${genre.name}</span>`).join('') : ''}
              <h3>${movie.title}</h3>
              <p>Rating: ${movie.vote_average}</p>
              <p>Release Date: ${movie.release_date}</p>
            </div>
          </a>
        </div>`;
      newReleasesContainer.append(movieElement);
    });
    $('.new-releases').slick('refresh');
                });

            // TV Shows
            $('.tv-shows').slick({
                slidesToShow: 8,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 640,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

            fetch('https://api.themoviedb.org/3/discover/tv?api_key=77555ae36bb8bc2ae287fceee7f78c1c')
                .then(response => response.json())
                .then(data =>{
                    const tvShowsContainer = $('.tv-shows');
                    data.results.forEach(show => {
  const showElement = `
    <div class="movie-item">
      <a href="tv-show.php?id=${show.id}">
        <img src="https://image.tmdb.org/t/p/w500/${show.poster_path}" alt="${show.name}">
        <div class="details">
          ${show.genres ? show.genres.map(genre => `<span>${genre.name}</span>`).join('') : ''}
          <h3>${show.name}</h3>
          <p>Rating: ${show.vote_average}</p>
          <p>First Aired: ${show.first_air_date}</p>
        </div>
      </a>
    </div>`;
  tvShowsContainer.append(showElement);
                    });
                    $('.tv-shows').slick('refresh');
                });
        });

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