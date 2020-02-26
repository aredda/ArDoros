<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <?php include "views/fragments/dependencies.php"; ?>
</head>
<body class="bg-zero">
    <div class="container centralized">
        <div class="row">
            <div class="col-md-6 offset-md-3 content-holder p-5 shadow-lg">
                <h1 class='text-center text-primary mb-4'><a href='index.php'>أردروس</a></h1>
                <form>
                    <div class="form-group">
                        <label class='font-weight-bold text-second'>اسم الأدمين</label>
                        <input placeholder='اسم الأدمين' type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class='font-weight-bold text-second'>كلمة المرور</label>
                        <input placeholder='كلمة المرور' type="password" class="form-control">
                    </div>
                    <button class="button inverse-first rounded shadow text-white text-center py-2 bg-grd-first btn-block">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>