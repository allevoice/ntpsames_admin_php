<?php
$url = '../';
$title="Mon title";
$title_page="Contact";
include $url."includes/header.php";
?>


    <section class="section dashboard">
        <div class="row">



                <section class="section contact">

                    <div class="row gy-4">


                        <div class="row">
                        <?php include $url."contact/content_infocard.php"?>

                       </div>

                        <div class="row">
                            <?php include $url."contact/content_seo.php"?>

                            <?php include $url."contact/content_sociales.php"?>



                        </div>



                    </div>

                </section>


        </div>
    </section>

<?php
include $url."includes/footer.php";
?>