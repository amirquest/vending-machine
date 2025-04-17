<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>درحال انتقال به درگاه پرداخت</title>
</head>
<body>

<form method="<?php echo htmlentities($method) ?>" action="<?php echo htmlentities($action) ?>">
    <?php
    foreach ($inputs as $name => $value): ?>
    <input type="hidden" name="<?php echo htmlentities($name) ?>" value="<?php echo htmlentities($value) ?>">
    <?php
    endforeach; ?>
</form>
<script>
    document.forms[0].submit();
</script>
</body>
</html>
