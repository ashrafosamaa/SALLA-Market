<?php
    session_start();
    include_once ('includes/header.php');
    require_once 'app/connect.php';
    $page_title = 'Home';
?>  

    <main id="main-page">
        <div class="about-team">
            <h1>Welcome to SALLA Market</h1> 
            <p>We are an organization which seek for helping our users to buy and sell products on internet</p>
        </div>
        <div class="team">
            <div class="team-member">
            <img src='https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortWaved&accessoriesType=Blank&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=Angry&mouthType=Smile&skinColor=Light'/>
                <h1>ASHRAF OSAMA</h1>
                <h6>Web Developer</h6>
            </div>
        </div>
        <div class="contact">
            <h4>You can contact us from <a href="mailto:ashrafosama667@gmail.com">HERE</a></h4>
        </div>

    </main>

<?php include_once ('includes/footer.php'); ?>
