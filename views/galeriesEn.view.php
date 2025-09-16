<link href="public/css/.css" rel="stylesheet">


<!-- Bootstrap video carousel -->
<div>
    <h1 class="roboto-font mt-5 mb-3">Video Gallery</h1><br>
    <div id="carouselExampleDarkVideos" class="carousel carousel-dark slide">
        <div class="carousel-inner">
            <!-- Replace the video links with your own video links -->
            <div class="carousel-item active">
                <video id="video1" class="d-block mx-auto" controls style="max-height: 60vh;">
                    <source src="public/assets/videos/video1.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="carousel-caption d-none d-md-block"></div>
            </div>
            <div class="carousel-item">
                <video id="video2" class="d-block mx-auto" controls style="max-height: 60vh;">
                    <source src="public/assets/videos/video2.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="carousel-caption d-none d-md-block"></div>
            </div>
            <div class="carousel-item">
                <video id="video3" class="d-block mx-auto" controls style="max-height: 60vh;">
                    <source src="public/assets/videos/video3.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="carousel-caption d-none d-md-block"></div>
            </div>
            <div class="carousel-item">
                <video id="video4" class="d-block mx-auto" controls style="max-height: 60vh;">
                    <source src="public/assets/videos/video4.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="carousel-caption d-none d-md-block"></div>
            </div>
            <!-- Add more videos here -->
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDarkVideos" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDarkVideos" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>


<div>
    <!-- Bootstrap photo carousel -->
    <br><br><br>
    <h1 class="roboto-font">Photo Gallery</h1><br>
    <div id="carouselExampleDark" class="carousel carousel-dark slide">
        <div class="carousel-inner">


            <div class="carousel-item active"><br>
                <img src="public/assets/images/Carou3.jpg" class="d-block mx-auto img-fluid" style="max-height: 60vh;" alt="...">
                <div class="carousel-caption d-none d-md-block"></div>
            </div>


            <div class="carousel-item"><br>
                <img src="public/assets/images/Carou4.jpg" class="d-block mx-auto img-fluid" style="max-height: 60vh;" alt="...">
                <div class="carousel-caption d-none d-md-block"></div>
            </div>


            <div class="carousel-item"><br>
                <img src="public/assets/images/Carou6.jpg" class="d-block mx-auto img-fluid" style="max-height: 60vh;" alt="...">
                <div class="carousel-caption d-none d-md-block"></div>
            </div>


            <div class="carousel-item"><br>
                <img src="public/assets/images/Carou15.jpg" class="d-block mx-auto img-fluid" style="max-height: 60vh;" alt="...">
                <div class="carousel-caption d-none d-md-block"></div>
            </div>


            <div class="carousel-item"><br>
                <img src="public/assets/images/Carou21.jpg" class="d-block mx-auto img-fluid" style="max-height: 60vh;" alt="...">
                <div class="carousel-caption d-none d-md-block"></div>
            </div>


        </div>


        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button><br />
    </div>
</div>


<footer>
    <div class="container-fluid">
        <?php include_once "views/common/footer.php"; ?>
    </div>
</footer>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var carouselVideos = document.getElementById('carouselExampleDarkVideos');
        carouselVideos.addEventListener('slide.bs.carousel', function(event) {
            var videos = carouselVideos.querySelectorAll('video');
            videos.forEach(function(video) {
                video.pause(); // Stop all currently playing videos
            });
        });
    });
</script>