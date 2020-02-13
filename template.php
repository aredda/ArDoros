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
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $this->title; ?></title>
            <?php include "fragments/dependencies.php"; ?>
        </head>
        <body class="bg-zero">
            <div class="container py-5">
                <!-- Header -->
                <div class="row mb-4">
                    <div class="col-md-9">
                        <div class="search-panel">
                            <button class="button bg-grd-first m-0 p-3">ابحث</button>
                            <input class="m-0 p-3" name="title" placeholder="عنوان الدرس..." />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h1 class="text-primary font-weight-bold">أردروس</h1>
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
                        <div class="nav-item">
                            <div class="nav-item-header bg-grd-second">
                                <a>مستوى</a>
                                <span>&rtrif;</span>
                            </div>
                            <div class="nav-item-body bg-white">
                                <a href="#" class="nav-item">مادة</a>
                                <a href="#" class="nav-item">مادة</a>
                                <a href="#" class="nav-item">مادة</a>
                            </div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-item-header bg-grd-second">
                                <a>مستوى</a>
                                <span>&rtrif;</span>
                            </div>
                        </div>
                        <div class="nav-item">
                            <div class="nav-item-header bg-grd-second">
                                <a>مستوى</a>
                                <span>&rtrif;</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Loading purposes -->
            <div class="loader shadow-sm"></div>
        </body>
        </html>
        <?php
    }
}