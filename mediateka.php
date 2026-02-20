<?php
session_start();

require('klaseak/com/leartik/daw24asdo/produktuak/produktua_db.php');
require('klaseak/com/leartik/daw24asdo/produktuak/produktua.php');

use com\leartik\daw24asdo\produktuak\ProduktuaDB as PDB;
use com\leartik\daw24asdo\produktuak\Produktua;


$produktuak = PDB::selectProduktuak();
if ($produktuak === null) {
    $produktuak = [];
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denda - Mediateka</title>
    <link rel="stylesheet" href="css/estiloak.css?v=<?php echo time(); ?>">
</head>
<body>
    
    
    <div class="header">
        <h1>Mediateka</h1>
    </div>
    <div class="logo media"><img src="https://asdo-s3.s3.eu-north-1.amazonaws.com/logo.png" alt="Denda Logo" class="logo"></div>
    <div class="nav media">
        <a href="index.php">Hasiera</a>
        <a href="katalogoa.php">Katalogoa</a>
    
    </div>

    <div class="container">        
        
        </div>

        
        <div class="section media-section">
            <h2>Irudiak (Produktuen Carousel-a)</h2>
            <div class="carousel-container">
                <button class="carousel-btn prev" onclick="moveCarousel(-1)">&#10094;</button>
                <div class="carousel-wrapper">
                    <div class="carousel-track" id="track">
                        <?php foreach ($produktuak as $produktua): ?>
                            <div class="carousel-slide">
                                <?php $img = $produktua->getIrudia() ? 'img/'.htmlspecialchars($produktua->getIrudia()) : 'img/'.htmlspecialchars($produktua->getId()).'.jpg'; ?>
                                <img src="<?php echo $img; ?>" 
                                     alt="<?php echo htmlspecialchars($produktua->getIzena(), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="carousel-caption">
                                    <h3><?php echo htmlspecialchars($produktua->getIzena(), ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p>
                                        <?php if ($produktua->getDeskontua() > 0): ?>
                                            <span style="text-decoration: line-through;"><?php echo number_format($produktua->getPrezioa(), 2, ',', '.'); ?>€</span> <strong style="color: #e74c3c;"><?php echo number_format($produktua->getPrezioaDeskontuarekin(), 2, ',', '.'); ?>€</strong>
                                        <?php else: ?>
                                            <?php echo number_format($produktua->getPrezioa(), 2, ',', '.'); ?> €
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="carousel-btn next" onclick="moveCarousel(1)">&#10095;</button>
            </div>
         </div>
        
        <div class="section media-section">
            <h2>Audio Zone</h2>
            
            <?php
            
            $mp3_files = glob("mp3/*.mp3");
            ?>
            
            <div class="media-item"> 
                <label for="audioSelect">Aukeratu audioa:</label>
                <select id="audioSelect" onchange="changeAudio(this.value)">
                    <?php 
                    if ($mp3_files) {
                        foreach ($mp3_files as $index => $file) {
                            $filename = basename($file);                            
                            $selected = ($index === 0) ? 'selected' : '';
                            echo "<option value=\"$file\" $selected>$filename</option>";
                        }
                    } else {
                        echo "<option value=\"\">Ez dago audiorik</option>";
                    }
                    ?>
                </select>

                <br><br>

                <audio id="myAudio" controls>
                    <?php 
                    $initial_audio = $mp3_files ? $mp3_files[0] : '';
                    if ($initial_audio) {
                        echo "<source src=\"$initial_audio\" type=\"audio/mpeg\">";
                    }
                    ?>
                    Your browser does not support the audio element.
                </audio>
            </div>

            <div class="media-item">
                <button onclick="document.getElementById('myAudio').play()">Play</button>
                <button onclick="document.getElementById('myAudio').pause()">Pause</button>
                <button onclick="document.getElementById('myAudio').currentTime = 0">Restart</button>
            </div>
        </div>

       
        <div class="section media-section">
            <h2>Video Zone</h2>

            <?php
            
            $mp4_files = glob("mp4/*.mp4");
            ?>

            <div class="media-item">
                <label for="videoSelect">Aukeratu bideoa:</label>
                <select id="videoSelect" onchange="changeVideo(this.value)">
                    <?php 
                    if ($mp4_files) {
                        foreach ($mp4_files as $index => $file) {
                            $filename = basename($file);
                            $selected = ($index === 0) ? 'selected' : '';
                            echo "<option value=\"$file\" $selected>$filename</option>";
                        }
                    } else {
                        echo "<option value=\"\">Ez dago bideorik</option>";
                    }
                    ?>
                </select>

                <br><br>

                <video id="myVideo" width="400" controls>
                    <?php 
                    $initial_video = $mp4_files ? $mp4_files[0] : '';
                    if ($initial_video) {
                        echo "<source src=\"$initial_video\" type=\"video/mp4\">";
                    }
                    ?>
                    Your browser does not support HTML video.
                </video>
            </div>

            <div class="media-item">
                <div style="margin-bottom: 15px;">
                    <button onclick="document.getElementById('myVideo').play()">Play Video</button>
                    <button onclick="document.getElementById('myVideo').pause()">Pause Video</button>
                    <button onclick="toggleVideoSize()">Cambiar Tamaño</button>
                </div>    
   </div>

    <script>
        
        function changeAudio(sourceUrl) {
            const audio = document.getElementById('myAudio');
            audio.src = sourceUrl;
            audio.load();
            audio.play();
        }

        function changeVideo(sourceUrl) {
            const video = document.getElementById('myVideo');
            video.src = sourceUrl;
            video.load();
            video.play();
        }

        
        let currentIndex = 0;
        const track = document.getElementById('track');
        const slides = document.querySelectorAll('.carousel-slide');
        const totalSlides = slides.length;
        let autoPlayInterval;

        function moveCarousel(direction) {
            if (totalSlides === 0) return;

            currentIndex += direction;

            if (currentIndex < 0) {
                currentIndex = totalSlides - 1;
            } else if (currentIndex >= totalSlides) {
                currentIndex = 0;
            }

            updateCarousel();
        }

        function updateCarousel() {
            if (totalSlides === 0) return;
            const slideWidth = slides[0].clientWidth;
            track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
        }

        
        function startAutoPlay() {
            if (totalSlides === 0) return;
            stopAutoPlay(); 
            autoPlayInterval = setInterval(() => {
                moveCarousel(1);
            }, 3000); 
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        
        if (totalSlides > 0) {
            startAutoPlay();
            updateCarousel(); 
        }

        
        const carouselContainer = document.querySelector('.carousel-container');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', stopAutoPlay);
            carouselContainer.addEventListener('mouseleave', startAutoPlay);
        }
        
        
        window.addEventListener('resize', () => {
             updateCarousel();
        });

        
        function toggleVideoSize() {
            const video = document.getElementById('myVideo');
            if (video.width === 400) {
                video.width = 600;
            } else {
                video.width = 400;
            }
        }
    </script>
</body>
</html>
