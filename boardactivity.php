<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">


    <title>BoardActivities</title>

    <link rel="icon" type="image/jpg" href="favicon.jpg">

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <!-- DatePicker JS -->
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <!-- DataTable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.15/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/datatables.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/raphael.min.js"></script>
    <!-- DatePicker CSS -->
    <link href="DatePicker/jquery-ui.css" rel="stylesheet" type="text/css">
    <link href="DatePicker/jquery-ui.structure.min.css" rel="stylesheet" type="text/css">
    <link href="DatePicker/jquery-ui.theme.min.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/my-style.css" rel="stylesheet" type="text/css">
    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Morris -->
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/raphael.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.js"></script>
</head>

<body>

<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Flash dev Activity & Worklog</a>
        </div>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li>
                    <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                </li>
                <li>
                    <a href="boardactivity.php"><i class="fa fa-fw fa-file"></i> Board Activities</a>
                </li>
                <li style="display: none" id="buttonEmail">
                    <a onclick="myApp2.sendMail()"><i class="fa fa-fw fa-mail-forward"></i>Rapport to Mail</a>
                    <!-- The Modal -->
                    <div id="myModal" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <div id="textModal"></div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>
    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        BoardActivities
                    </h1>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-calendar" aria-hidden="true"></i> Select a date </h3>
                        </div>
                        <div id="datepicker" class="panel-body"></div>
                        <p>
                            Dates:
                            <input type="text" id="input1" size="10">
                            <input type="text" id="input2" size="10">
                        </p>
                        <script>
                            $(function() {
                                $("#datepicker").datepicker({
                                    maxDate: 0,
                                    beforeShowDay: function(date) {
                                        var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat , $("#input1").val());
                                        var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat , $("#input2").val());
                                        return [true, date1 <= date && date <= date2 ? "selected" : ""];
                                    },
                                    onSelect: function(dateText, inst) {
                                        var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat , $("#input1").val());
                                        var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat , $("#input2").val());

                                        if (!date1 || date2) {
                                            $("#input1").val(dateText);
                                            $("#input2").val("");
                                            $(this).datepicker("option", "minDate", dateText);
                                        } else {
                                            $("#input2").val(dateText);
                                            $(this).datepicker("option", "minDate", null);


                                        }
                                        if (($("#input1").val() != '') && ($("#input2").val() != '')) {
                                            $('#selectBoard').css("visibility", "visible");
                                        }
                                    }
                                });

                                $.datepicker.setDefaults({
                                    dateFormat: 'dd-mm-yy',
                                });
                            });
                        </script>
                    </div>
                </div>

                <div id="loader" style="display:none;"></div>

                <div class="col-lg-3" id="selectBoard" style="visibility: hidden;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-caret-square-o-down" aria-hidden="true"></i> Select a board </h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            ini_set('display_errors', 1);
                            require_once __DIR__."/vendor/autoload.php";
                            $client = new \ProjectPHP\ControllerBoard();
                            $listProject = $client->projectBoard();
                            ?>
                            <SELECT name='Board' id='listeBoard' class="form-control" onchange="myApp2.onChangeSelect()">
                                <option>Please select a board.</option>
                                <?php
                                /** @var \ProjectPHP\Project $project */
                                foreach ($listProject as $project) {
                                    /** @var \ProjectPHP\Board $board */
                                    foreach ($project->getBoards() as $board){
                                        echo "<OPTION value='{$board->getId()}'>  {$board->getId()} | {$board->getName()} </OPTION>";
                                    }
                                }
                                ?>
                            </SELECT>
                        </div>
                    </div>
                    <button id="btnValidate" class="btn btn-xs btn btn-primary" style="visibility: hidden;" onclick="myApp2.callAjax()">
                        Validate
                    </button>
                </div>
            </div>
            <!-- /.row -->
            <div class="row" id="barHGraph">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bar-chart" aria-hidden="true"></i> LoggedTime Bar Graph</h3>
                        </div>
                        <div class="panel-body">
                            <div id="morris-bar-H-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="divCard">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-table" aria-hidden="true"></i> LoggedTime per developper Report</h3>
                        </div>
                        <div style=" margin: 10px" id="myDiv" class="table-responsive">
                            <table id="tableTasks" class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
                                <thead id="theadWTR">
                                <tr>
                                    <th>CardID</th>
                                    <th>Author</th>
                                    <th>Event</th>
                                    <th>Date</th>
                                    <th>Texte</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="loader" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        <a href="#" title="Retour haut de page"><img src="mint2.png"/></a>
    </div>
    <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- jQuery -->
<script src="js/plugins/morris/morris.js"></script>
<script src="js/plugins/morris/morris.min.js"></script>
<script src="js/plugins/morris/raphael.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
<script src="activityBoard.js"></script>
</body>
</html>