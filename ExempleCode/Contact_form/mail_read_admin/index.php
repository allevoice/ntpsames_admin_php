<?php


include '../class/Mainclass.php';
include '../class/Contact.php';

$data = new Contact();

$mailIds = $_GET['id'] ?? [];


if ( empty($mailIds)) {
    header('Location: ../index.php');
    exit;
}

$data->markStatus_read($mailIds, 1);
$value = $data->mark_mail_read($mailIds);


$url = "../";

include $url . 'include/header.php';
?>






    <div class="pagetitle">
        <h1>Dashboard</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">


                            <div class="card-body">

                                <?php include "read_content.php"?>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->

                </div>
            </div><!-- End Left side columns -->


            <?php include $url."include/sidebare_right.php"?>

        </div>
    </section>


<?php include $url.'include/footer.php'; ?>