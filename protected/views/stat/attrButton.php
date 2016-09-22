<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.09.2016
 * Time: 21:32
 */
Yii::app() -> clientScript -> registerScript("dateAttrButton","
    $('#dateAttrSelect span').click(function(){
        var loc = location.href;
        var sepInd = loc.indexOf('?');
        var locEnding = loc.substring(sepInd + 1);

        locEnding = locEnding.replace(new RegExp('(\\\?|&)?attr=[^\&]+'),'');

        var locBegin = loc.substring(0,sepInd);
        if (sepInd == -1) {
            locBegin = loc;
            locEnding = '';
        }
        loc = locBegin+'?'+locEnding;
        if (loc.indexOf('=') > -1) {
            loc += '&attr=' + $(this).attr('data-val');
        } else {
            loc += 'attr=' + $(this).attr('data-val');
        }
        //alert(loc);
        location.href = loc;
    });
")
?>
<div id="dateAttrSelect" class="navbar-collapse collapse in">
    <span class="btn btn-default navbar-btn <?php echo ($attr=="date") ? "active" : ""; ?>" data-val="date">По дате записи</span>
    <span class="btn btn-default navbar-btn <?php echo ($attr=="calledDate") ? "active" : ""; ?>" data-val="calledDate">По дате звонка</span>
</div>
