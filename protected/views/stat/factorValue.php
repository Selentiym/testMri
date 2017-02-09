<?php
/**
 * @type iFactorResult $factorResult
 * @type iFactor|null $view
 */
?>
<h2><?php echo $factorResult -> getId(); ?></h2>
<?php
$objs = $factorResult -> giveObjects();
$str = '';
if (!empty($objs)) {
    foreach ($objs as $item) {
        if ($view instanceof iFactor) {
            if (!($view -> apply($item) > 0)) {
                continue;
            }
        }
        $str .= $this -> renderPartial('/stat/_singleFactorable',[
            'model' => $item
        ], true);
    }
}
if (!$str) {
    echo "Не найдено ни одного захода.";
} else {
    ?>
    <table class='table table-stripped' style='margin-top: 10px'>
        <thead>
            <tr>
                <th>Время захода</th>
                <th>Фраза</th>
                <th>Телфин звонки</th>
                <th>ФИО</th>
                <th>MangoTalker</th>
                <th>Отчет</th>
                <th>Mobile</th>
                <th>Цена</th>
            </tr>
        </thead>
        <?php echo $str; ?>
    </table>
    <?php
}
?>
