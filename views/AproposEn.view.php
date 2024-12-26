<link href="public/css/.css" rel="stylesheet">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>

    <style>
        /* Styles for the hover animation effect */
        .grid h3:hover {
            cursor: pointer;
            /* Adds a pointer cursor effect */
            color: blue;
            /* Change color on hover */
            transition: color 0.3s ease-in-out;
            /* Smooth color transition */
        }

        /* Styles for the click animation effect */
        .clicked-effect {
            animation: bounce 0.3s;
            /* Use the "bounce" animation for 0.3s */
        }

        /* Definition of the "bounce" animation */
        @keyframes bounce {
            0% {
                transform: translateY(0);
                /* Starting position */
            }

            50% {
                transform: translateY(-10px);
                /* First bounce upwards */
            }

            100% {
                transform: translateY(0);
                /* Return to the initial position */
            }
        }

        /* Styles for the fullscreen page */
        .fullscreen-page {
            display: none;
            /* By default, the page is hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            /* Semi-transparent background */
            z-index: 9999;
            /* Ensure the page is above everything else */
            color: white;
            padding: 20px;
            overflow-y: auto;
            /* Allows vertical scrolling if content is longer than the screen */
        }

        /* Media queries for smaller screens */
        @media (max-width: 600px) {
            .fullscreen-page {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="grid text-center roboto-font text-justify">
        <br>
        <div class="g-col-6">
            <h3>Our Values</h3>
        </div><br>
        <div class="g-col-6">
            <h3>Our Coaching Approach</h3>
        </div><br>
        <div class="g-col-6">
            <h3>Our Team</h3>
        </div><br>
        <div class="g-col-6">
            <h3>Coached Players</h3>
        </div><br>
        <div class="g-col-6">
            <h3>Partners</h3>
        </div><br><br>
    </div>

    <!-- Fullscreen page -->
    <div class="fullscreen-page" id="fullscreenPage">
        <!-- The content of the accueil.view.php page will be displayed here -->
    </div>

    <footer>
        <div class="container-fluid">
            <?php include_once "views/common/footer.php"; ?>
        </div>
    </footer>

    <?php include "public/js/script2.php" ?>

</body>

</html>
