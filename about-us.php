<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About US</title>
    <link rel="stylesheet" href="about-us.css">
</head>
<body>
    <?php
// Вклучување на navbar.php
include('navbar.php'); // или require('navbar.php');
?>

    <div class="container">
        <div class="team-section">
            <div class="team-card">
                <img src="david.png" alt="David">
                <p>Давид Герасимов</p><hr>
                <p>индекс бр.102747  </p><hr>
                <p>Е-пошта:</p>
                <P>david.102747@student.ugd.edu.mk</p><hr>
                    <p>тел:+389 ## ### ###</p>
            </div>
            <div class="team-card">
                <img src="andrej.jpg" alt="Andej">
                <p>  Андреј Георгиев</p><hr>
                <p>индекс бр.102737</p><hr>
                <p>Е-пошта:</p>
                    <p>andrej.102737@student.ugd.edu.mk</p><hr>
                    <p>тел:+389 ## ### ###</p>
            </div>
            <div class="team-card">
                <img src="alek.jpg" alt="Alek">
                <p>  Алек Даниловски     
                </p><hr>
                <p>индекс бр.102729  </p><hr>
                <p>Е-пошта:</p>
                <p>alek.102729@student.ugd.edu.mk</p><hr>
                    <p>тел:+389 ## ### ###</p>
            </div>
        </div>

        <div class="divider"></div>
        

        <div class="about-section">
            <p>Welcome to our innovative social media platform! We are a dedicated team of developers united by our 
            passion for technology and our vision to create a unique microblogging experience.</p>

    <div class="card">
    <p class="position">Andrej Georgiev - Tester and Front-End Developer</p>
    <p>Andrej is the genius behind the seamless user experience of our platform. With an exceptional eye for design and an outstanding ability 
    to identify potential issues before they arise, Andrej ensures our users enjoy a smooth and visually appealing interface.</p></div>

    <div class="card">
    <p class="position">Alek Danilovski - Back-End Developer and Database Manager</p>
    <p>Alek is the architect of our platform's robust infrastructure. With a deep understanding of databases, APIs, and server logic,
    he builds and maintains the backbone of our system. Alek's meticulous approach guarantees top-notch performance and security,
    enabling us to support an ever-growing number of users.</p></div>

    <div class="card">
    <p class="position">David Gerasimov - Full Stack Developer</p>
    <p>As a versatile front-end and back-end developer, you bridge the gap between design and functionality.
    Your unique ability to view projects from both perspectives ensures that our platform is not 
    only beautiful but also highly functional and responsive. Your expertise allows us to quickly transform innovative ideas into working features.</p></div>

    <div class="community">
    <p>Together, we are committed to creating a vibrant online community where users can freely express themselves, share brief updates, 
        and connect through shared interests. Our collaborative spirit and diverse skill sets help us continuously improve and innovate,
         bringing you the best possible social media experience.</p>

    Join us on this exciting journey as we redefine the way people connect and share in the digital world!</p>
        </div>
    </div>
</body>
</html>
