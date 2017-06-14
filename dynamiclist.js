$(document).ready(function() {
    myApp.hideAll();
});

var myApp = {}; //Déclaration namespace

//Déclaration variables//
myApp.table = $('#tableTasks').DataTable({
    "aoColumns": [
        {data: "TaskID"},
        {data: "Title",},
        {data: "Assignee"},
        {data: "Column"},
        {data: "Lane"},
        {data: "Size"},
        {data: "TimeLogged"},
        {
            data: "Producter",
            defaultContent: ""
        },
        {
            data: "Allocated",
            defaultContent: ""
        },
        {data: "Rapport"}
    ],
    "searching": true,
    "autocomplete" : {
        autofill : true,
        cacheLength : 10,
        max : 6,
        autofocus : true,
        highlight : true,
        mustMatch : true,
        selectFirst : true
    },
    "retrieve": true,
    "autoFill": true,
    "orderClasses": false,
    "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        if (((aData.Column)== "Validated by business")||((aData.Column)== "Pushed in Prod")||((aData.Column)== "Ready for testing")||((aData.Column)== "Testing")){
            $(nRow).removeClass('odd');
            $(nRow).removeClass('even');
            $(nRow).addClass('success');
        }
        else if (((aData.Column)== "Testing failed")||((aData.Column)== "Waiting integration")||((aData.Column)== "In Progress")||
            ((aData.Column)== "Dev Blocked")||((aData.Column)== "Test Environment")||((aData.Column)== "Pull request")){
            $(nRow).removeClass('odd');
            $(nRow).removeClass('even');
            $(nRow).addClass('warning');
        }
        else if (((aData.Column)== "Evaluated")||((aData.Column)== "To be inherited")||((aData.Column)== "In Progress")){
            $(nRow).removeClass('odd');
            $(nRow).removeClass('even');
            $(nRow).addClass('active');
        }
    }
});
myApp.board = '';
myApp.fromdate = '';
myApp.todate = '';
myApp.totalLog = 0;
myApp.totalSize = 0;
myApp.dataForVBarChart = [];

//Fonctions//
myApp.callAjax = function() {
    this.updateValues();
    this.showPage();

        $.ajax({
            type:'POST',
            url:'ajax.php',
            data: {
                action: 'getTaskByBoard',
                boardId: myApp.board,
                fromdate : myApp.fromdate,
                todate : myApp.todate
            },
            success: function (data) {
                var result = $.parseJSON(data);

                    if (result['status'] == 'success') {
                        myApp.clearData();
                        console.log(result);
                        if (result['result'] == null) {
                            $("#loader").css("display","none");
                            myApp.hideAll();
                        }
                        else {
                            var tasks = result['result'];
                            var longI = result['result'].length;
                            for (var i = 0; i < longI; i++) {
                                var longJ = tasks[i]['customfields'].length;
                                for (var j = 0; j < longJ; j++) {
                                    if ((tasks[i]['customfields'][j]['name']) == 'Product Owner') {
                                        var productOwner = tasks[i]['customfields'][j]['value'];
                                    }
                                    else if ((tasks[i]['customfields'][j]['name']) == 'Allocated to') {
                                        var allocatedTo = tasks[i]['customfields'][j]['value'];
                                    }
                                }
                                if ((tasks[i]['size'] == null) || (tasks[i]['logedtime'] == null)) {
                                    var ratio = 0;
                                }
                                else {
                                    var ratio = ((tasks[i]['logedtime']) / (tasks[i]['size']) * 100).toFixed(2);
                                    var donnees = [
                                        {
                                            TaskID: tasks[i]['taskid'],
                                            Title: tasks[i]['title'],
                                            Assignee: tasks[i]['assignee'],
                                            Column: tasks[i]['columnname'],
                                            Lane: tasks[i]['lanename'],
                                            Size: tasks[i]['size'],
                                            TimeLogged: tasks[i]['logedtime'],
                                            Producter: productOwner,
                                            Allocated: allocatedTo,
                                            Rapport: ratio
                                        }
                                    ];
                                    myApp.table.rows.add(donnees).draw();
                                    myApp.totalLog += tasks[i]['logedtime'];
                                    var sizeBis=tasks[i]['size'];
                                    myApp.totalSize += Number(sizeBis);
                                    myApp.dataForVBarChart.push(
                                        {
                                            taskId: tasks[i]['taskid'],
                                            loggedTime: (tasks[i]['logedtime']).toFixed(2),
                                            size: tasks[i]['size']
                                        }
                                    );
                                }
                            }
                            myApp.Vchart = Morris.Bar({
                                element: 'morris-bar-V-chart',
                                data: myApp.dataForVBarChart,
                                xkey: 'taskId',
                                ykeys: ['loggedTime', 'size'],
                                labels: ['LoggedTime', 'Size'],
                                hideHover: 'auto',
                                resize: true,

                            });
                            $("#loader").css("display","none");
                            $('#tableTasks tbody').on('click', 'tr', function () {
                                myApp.clickTask(myApp.board, myApp.table.row(this).data().TaskID);
                                $(window).scrollTop(0);
                            });
                            myApp.initLoader();
                            myApp.initAverage();
                            myApp.showAll();
                        }
                    }


            }
        });
    };// call à l'API

myApp.clearData = function() {
    myApp.table.clear().draw();
    myApp.dataForVBarChart=[];
    $("#morris-bar-V-chart").empty();
    $("#morris-donut-chart").empty();
    $("#bluecircle").empty();
}; // lorsque l'on relance un appel on vide au préalable tableau et graphiques

myApp.updateValues = function () {
    this.board = $('#listeBoard').val();
    this.fromdate = $("#input1").val();
    this.todate = $("#input2").val();
}; // instensiation des variables à l'appel API

myApp.initAverage = function (){
    var moy = (myApp.totalLog/myApp.totalSize).toFixed(1);
    if (moy <= 1){
        $("#bluecircle").append('<span style="color:green;">'+moy+'</span><div class="slice"><div class="bar"></div><div class="fill"></div></div> ');
    }
    else
    {
        $("#bluecircle").append('<span style="color:red;">'+moy+'</span><div class="slice"><div class="bar"></div><div class="fill"></div></div> ');
    }
    $('#divAverage').css("visibility", "visible");

}; //déclaration de l'average

myApp.initLoader = function (){
    $("#bluecircle").percircle();
}; //déclaration loader

myApp.showPage = function() {
    $("#loader").css("display", "block");
};// affichage du loader

myApp.clickTask = function(board,id) {
    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: {
            action: 'getTaskDetails',
            boardId: board,
            taskId: id
        },
        success: function (data)
        {
            $("#morris-donut-chart").empty();
            var result = $.parseJSON(data);
            if (result['status'] == 'success')
            {
                var tasks = result['result'];
                if (tasks['size']== null)
                {
                    var p1 = 0;
                    var p2 =  (tasks['loggedtime']).toFixed(2);
                }
                else if (tasks['loggedtime']== null)
                {
                    p1 =  tasks['size'];
                    p2 = 0;
                }
                else
                {
                    p1 =  tasks['size'];
                    p2 =  tasks['loggedtime'];
                    if (p1>p2){
                        p2=(p2/p1).toFixed(2);
                        p1=1;
                    }
                }
                var donuts = Morris.Donut
                ({
                    element: 'morris-donut-chart',
                    data:
                    [
                        {
                            label: 'Size',
                            value: p1
                        },
                        {
                            label: 'LoggedTime',
                            value: p2
                        }
                    ],
                    resize: true
                });
                $('#donutsChart').css("visibility", "visible");
            }
        }
    });

}; // fonction affichage donutsChart au click d'une ligne du tableau

myApp.disableCollapse = function(){
    if (($("#collapse").attr('class') == 'fa fa-chevron-down fa-lg') || ($("#collapse").attr('class') == 'fa fa-chevron-down fa-lg collapsed')){
        $("#collapse").removeClass('fa fa-chevron-down fa-lg');
        $("#collapse").addClass('fa fa-chevron-up fa-lg');
    }
    else if (($("#collapse").attr('class') == 'fa fa-chevron-up fa-lg') || ($("#collapse").attr('class') == 'fa fa-chevron-up fa-lg collapsed'))
    {
        $("#collapse").removeClass('fa fa-chevron-up fa-lg');
        $("#collapse").addClass('fa fa-chevron-down fa-lg');
    }
};

myApp.onChangeSelect = function() {
    if ($('#listeBoard').val() == ("Please select a board.")){
        $('#listeBoard').css("color","red");
        myApp.hideAll();
    }
    else {
        $('#listeBoard').css("color","#555");
        $('#btnValidate').css("visibility", "visible");
    }

}; //button visible quand select change

myApp.hideAll = function() {
    $('#btnValidate').css("visibility", "hidden");
    $('#donutsChart').css("visibility", "hidden");
    $('#divAverage').css("visibility", "hidden");
    $('#divTable').css("visibility", "hidden");
    $('#barVGraph').css("visibility", "hidden");
}; //cache tout si le select n'est pas bon

myApp.showAll = function(){
    $('#divAverage').css("visibility", "visible");
    $('#divTable').css("visibility", "visible");
    $('#barVGraph').css("visibility", "visible");
}; //show quand les data sont chargées
