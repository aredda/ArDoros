<?php

class Template
{
    private $title;
    private $content;

    public static function useTemplate ($title, $content)
    {
        new Template ($title, $content);
    }

    public function __construct ($title, $content)
    {
        $this->title = $title;
        $this->content = $content;

        // Merge the content page and the template
        $this->render ();
    }

    private function render ()
    {
        global $rootPath;

        include_once "src/utilities/loader.php";
        include_once "src/utilities/translator.php";

        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $this->title; ?></title>
            <?php include "views/fragments/dependencies.php"; ?>
        </head>
        <body class="bg-zero container-fluid">
            <div class="row">
                <div class="col-lg-2 banner">
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- Banner -->
                    <ins class="adsbygoogle"
                        style="display:block"
                        data-ad-client="ca-pub-7064691516440282"
                        data-ad-slot="2122229053"
                        data-ad-format="auto"
                        data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
                <div class="col-lg-8 py-5">
                    <!-- Header -->
                    <div class="row mb-4">
                        <div class="col-md-9">
                            <div class="search-panel">
                                <button class="button btn-search bg-grd-first m-0 p-3">ابحث</button>
                                <input class="m-0 p-3" name="title" placeholder="...عنوان الدرس" />
                            </div>
                        </div>
                        <!-- Brand Section -->
                        <div class="col-md-3 brand">
                            <a href="index.php"><h1 class="text-primary font-weight-bold">أردروس</h1></a>
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
                            <?php include "views/fragments/navbar.php"; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 banner">
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- Banner -->
                    <ins class="adsbygoogle"
                        style="display:block"
                        data-ad-client="ca-pub-7064691516440282"
                        data-ad-slot="2122229053"
                        data-ad-format="auto"
                        data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
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