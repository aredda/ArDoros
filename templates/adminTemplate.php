<?php

class AdminTemplate
{
    private $title;
    private $content;

    public static function useTemplate($title, $content)
    {
        new AdminTemplate($title, $content);
    }

    public function __construct($title, $content)
    {
        $this->title = $title;
        $this->content = $content;

        // Merge the content page and the template
        $this->render();
    }

    private function render()
    {
        include_once "src/utilities/loader.php";
        include_once "src/utilities/translator.php";
        include_once "src/utilities/authenticator.php";
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $this->title; ?></title>
            <?php include "views/fragments/dependencies.php"; ?>
        </head>

        <body class="bg-zero">
            <?php
            // Redirect to the signing page
            if (!Authenticator::isAuthenticated()) {
                header('refresh: 1; url= sign.php');

                echo "<script> popNotification ('عذرا', 'المرجو تسجيل الدخول أولا', 'error') </script>";
            }
            ?>
            <div class="container py-5">
                <!-- Header -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <a href='menu.php'>
                            <h1 class='half-scaled m-0 text-left text-second'><i class="fal fa-solar-panel"></i>لوحة التحكم</h1>
                        </a>
                    </div>
                    <div class="col-md-3 offset-md-6">
                        <a href="index.php">
                            <h1 class="text-primary font-weight-bold">أردروس</h1>
                        </a>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <!-- Content Container -->
                    <div class="col-md-9">
                        <!-- Include content here -->
                        <?php include $this->content; ?>
                    </div>
                    <!-- Navigation Bar -->
                    <div class="col-md-3">
                        <?php include "views/fragments/navbar_admin.php"; ?>
                    </div>
                </div>
            </div>
            <!-- Loading purposes -->
            <div class="loader shadow-sm"></div>
            <!-- Notification purposes -->
            <div class="notification">
                <div class="notification-header">This is the header</div>
                <div class="notification-body bg-zero">This is the body</div>
                <div class="notification-footer bg-light">
                    <button class="btn btn-sm btn-danger btn-close">اغلاق</button>
                </div>
            </div>
            <!-- Overlay -->
            <div class="overlay"></div>
        </body>

        </html>
<?php
    }
}
