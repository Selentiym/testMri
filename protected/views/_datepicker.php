
<?php
/**
 *
 */
$data = new Data();
$from = (int)$get['from'] ? (int)$get['from'] : time() - 86400*5;
$to = (int)$get['to'] ? (int)$get['to'] : time();
$range = array('from' => $from, 'to' => $to);
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bundle-bundle_daterangepicker_defer.css');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/bundle-bundle_daterangepicker_defer.js');

$url = str_replace("%startTime%","' + start.unix() + '", $url);
$url = str_replace("%endTime%","' + end.unix() + '", $url);

Yii::app()->getClientScript()->registerScript('DatePickerRange',"
    $(function () {

        $('#reportrange').daterangepicker({
            format: 'DD.MM.YYYY',
            startDate: '".date('d.m.Y', $range['from'])."',
            endDate: '".date('d.m.Y', $range['to'])."',
            minDate: '01.05.2015',
            maxDate: ".strtotime("+1 month").",
            dateLimit: { months: 3 },
            ranges: {
                'Сегодня': [moment(), moment()],
                'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
                'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
                'Текущий месяц': [moment().startOf('month'), moment().endOf('month')],
                'Предыдущий месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-default',
            separator: ' по ',
            locale: {
                applyLabel: 'Показать',
                cancelLabel: 'Отмена',
                fromLabel: 'С',
                toLabel: 'По',
                customRangeLabel: 'Выбрать даты',
                daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июлю', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                firstDay: 1
            }
        }, function (start, end, label) {
            //end.subtract(1, 'days');
            window.location.href = '".$url."';
        });

    });
", CClientScript::POS_END);
$translate = array(
    "am" => "дп",
    "pm" => "пп",
    "AM" => "ДП",
    "PM" => "ПП",
    "Monday" => "Понедельник",
    "Mon" => "Пн",
    "Tuesday" => "Вторник",
    "Tue" => "Вт",
    "Wednesday" => "Среда",
    "Wed" => "Ср",
    "Thursday" => "Четверг",
    "Thu" => "Чт",
    "Friday" => "Пятница",
    "Fri" => "Пт",
    "Saturday" => "Суббота",
    "Sat" => "Сб",
    "Sunday" => "Воскресенье",
    "Sun" => "Вс",
    "January" => "Января",
    "Jan" => "Янв",
    "February" => "Февраля",
    "Feb" => "Фев",
    "March" => "Марта",
    "Mar" => "Мар",
    "April" => "Апреля",
    "Apr" => "Апр",
    "May" => "Мая",
    "May" => "Мая",
    "June" => "Июня",
    "Jun" => "Июн",
    "July" => "Июля",
    "Jul" => "Июл",
    "August" => "Августа",
    "Aug" => "Авг",
    "September" => "Сентября",
    "Sep" => "Сен",
    "October" => "Октября",
    "Oct" => "Окт",
    "November" => "Ноября",
    "Nov" => "Ноя",
    "December" => "Декабря",
    "Dec" => "Дек",
    "st" => "ое",
    "nd" => "ое",
    "rd" => "е",
    "th" => "ое"
);
$_GET["from"] = $range["from"];
$_GET["to"] = $range["to"];
?>
<span id="reportrange" style="border-bottom: dotted 1px; font-size: 150%">
    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
    <span><small>с</small>
        <?php echo strtr(date("d F Y",$range['from']), $translate); ?>
        <small>по</small>
        <?php echo strtr(date("d F Y",$range['to']),$translate); ?>
    </span> <b class="caret"></b>
</span>
