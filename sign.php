<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <?php include "views/fragments/dependencies.php"; ?>
</head>
<body class="bg-zero">
    <?php 
        include 'src/utilities/authenticator.php';

        if (count ($_POST) > 0)
        {
            try
            {
                if (empty ($_POST['admin']) || empty ($_POST['pass']))
                    throw new Exception ('المرجو ادخال جميع المعلومات');
    
                if (!Authenticator::authenticate($_POST['admin'], $_POST['pass']))
                    throw new Exception ('معلومات غير صحيحة');
    
                // Redirect to control panel
                header ('Location: menu.php');
            }
            catch (Exception $e)
            {
                echo "<script> popNotification ('عذرا', '{$e->getMessage ()}', 'error'); </script>";
            }
        }
    ?>
    <div class="container centralized">
        <div class="row">
            <div class="col-md-6 offset-md-3 content-holder p-5 shadow-lg">
                <h1 class='text-center text-primary mb-4'><a href='index.php'>أردروس</a></h1>
                <form method='post'>
                    <div class="form-group">
                        <input name='admin' placeholder='اسم الأدمين' type="text" class="form-control" />
                    </div>
                    <div class="form-group">
                        <input name='pass' placeholder='كلمة المرور' type="password" class="form-control">
                    </div>
                    <button class="button inverse-second rounded shadow text-white text-center py-2 bg-grd-second btn-block">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </div>
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