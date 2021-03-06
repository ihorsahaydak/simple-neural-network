<?php
require_once 'handler.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Neural network (single-layer perceptron)</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
<body>
<div class="nn-images-data">
    <?php for($i = 0; $i < 10; $i++) : ?>
        <img src="images/<?php echo $i; ?>.png" onclick="submitForm('<?php echo $i; ?>');">
    <?php endfor; ?>
    <hr>
</div>
<div class="nn-form">
    <form id="nn-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="hidden" id="nn-form-value" name="n">
        <input type="hidden" name="delete" id="reset-data" onclick="resetData();">
        <input type="submit" id="nn-form" value="Apply" class="no-display">
    </form>
</div>
<div class="nn-out-content">
<?php
if (isset($_GET['delete']) && $_GET['delete'] == 1){
    for($i = 0; $i < 10; $i++) {
        $file = 'data/' . $i . '.txt';
        if (file_exists($file)) { unlink($file); }
    }
    echo 'Collected data was cleaned!';
} else {
    if ((isset($_GET['n'])) && ($_GET['n'] >= 0) && ($_GET['n'] <= 9)) {
        $n = (int) $_GET['n'];
        do{
            $errors = 0;
            for ($i = 0; $i < 10; $i++) {
                $element[$i] = new Handler($i);
                $element[$i]->preparePreDependencies($n);
                $isLimit[$i] = $element[$i]->isLimit();
                $say = '<b>-</b>';
                $say = $isLimit[$i] ? '+' : '-';
                echo $i . ' => ' . $say;
                if ($i == $n) {
                    if (!$isLimit[$i]) {
                        $element[$i]->teachPlus();
                        echo ' // ++';
                        $errors++;
                    }
                } else {
                    if ($isLimit[$i] == true) {
                        $element[$i]->teachMinus();
                        echo ' // --';
                        $errors++;
                    }
                }
                echo '<br>';
                $element[$i]->saveFileWeight();
            }
            echo '<br>';
            if ($errors == 0) {
                echo '<img src="images/'.$n.'.png"/><br>';
            }
        } while ($errors != 0);
    }
}
?>
</div>
<br>
<hr>
<button onclick="resetData();">Reset collected data!</button>
<script src="js/app.js"></script>
</body>
</html>
