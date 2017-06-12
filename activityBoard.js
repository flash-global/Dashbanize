var myApp2 = {};

//Déclaration variables//
myApp2.table = $('#tableTasks').DataTable({
    "aoColumns": [
        {data: "CardID"},
        {data: "Author",},
        {data: "Event"},
        {data: "Date"},
        {data: "Texte"},
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
        if (((aData.Event)== "Worklog updated")){
            $(nRow).removeClass('odd');
            $(nRow).removeClass('even');
            $(nRow).addClass('success');
        }
        else if (((aData.Event)== "Task moved")||((aData.Event)== "Task updated")){
            $(nRow).removeClass('odd');
            $(nRow).removeClass('even');
            $(nRow).addClass('active');
        }
        else if (((aData.Event)== "User has been mentioned")||((aData.Event)== "Comment added")||((aData.Event)== "Tags changed")){
            $(nRow).removeClass('odd');
            $(nRow).removeClass('even');
            $(nRow).addClass('warning');
        }
    }
});
myApp2.board = $('#listeBoard').val();
myApp2.fromdate = $("#input1").val();
myApp2.todate = $("#input2").val();
myApp2.tableLog = [];
myApp2.tableBar =[];

//Déclaration fonctions//
myApp2.callAjax = function() {
    this.updateValues();
    this.showPage();
    myApp2.clearData();
    $.ajax({
        type:'POST',
        url:'ajax.php',
        data: {
            action: 'getBoardActivities',
            boardId: myApp2.board,
            fromdate : myApp2.fromdate,
            todate : myApp2.todate
        },
        success: function (data) {
            var compteur = 0;
            var result = $.parseJSON(data);
            if (result['status'] == 'success') {
                var donnees = [];
                var tasks = result['result'];
                var longI = result['result'].length;
                for (var i = 0; i < longI; i++) {
                    var longJ = result['result'][i].length;
                    if ((longJ)>0){
                        for (var j= 0; j < longJ; j++)
                        {
                            var arrayParse = JSON.parse(result['result'][i][j]);
                            var longP = arrayParse['activities'].length;
                            if (longP>0){
                                for (var p=0 ; p < longP; p++){
                                        var cardid = arrayParse['activities'][p]['taskid'];
                                        var author = arrayParse['activities'][p]['author'];
                                        var event = arrayParse['activities'][p]['event'];
                                        var date = arrayParse['activities'][p]['date'];
                                        var texte = arrayParse['activities'][p]['text'];

                                        donnees.push(
                                            {
                                                CardID: cardid,
                                                Author: author,
                                                Event: event,
                                                Date: date,
                                                Texte: texte,
                                            });

                                        if (event == 'Worklog updated') {
                                            if (!isNaN(texte.charAt(0))) {
                                                if (isNaN(texte.charAt(2))) {
                                                    myApp2.tableLog.push(
                                                        {
                                                            assignee: author,
                                                            loggedTime: texte.charAt(0)
                                                        }
                                                    );
                                                }
                                                else {
                                                    myApp2.tableLog.push(
                                                        {
                                                            assignee: author,
                                                            loggedTime: texte.charAt(0) + texte.charAt(1) + texte.charAt(2) + texte.charAt(3)
                                                        }
                                                    );
                                                }
                                            }
                                        }
                                    }
                            }
                        }
                    }
                    else if(longJ==0){
                        compteur++;
                    }
                }
                $("#loader").css("display","none");
                if (compteur==15){
                    $("#morris-bar-H-chart").css("display","none");
                }
                else{
                    myApp2.table.rows.add(donnees).draw();
                    $("#morris-bar-H-chart").css("display","block");
                    var tableResult=[];
                    myApp2.tableLog.forEach(function (value) {
                        if (isNaN(tableResult[value.assignee])) {
                            tableResult[value.assignee] = 0;
                        }
                        tableResult[value.assignee] += parseFloat(value.loggedTime);
                    });
                    for (var key in tableResult){
                        myApp2.tableBar.push({
                            assignee : key,
                            loggedTime : (tableResult[key]).toFixed(3)
                        });
                    }
                    if (myApp2.tableBar.length!=0){
                        myApp2.Hchart = Morris.Bar({
                            element: 'morris-bar-H-chart',
                            data: myApp2.tableBar,
                            xkey: 'assignee',
                            ykeys: ['loggedTime'],
                            labels: ['loggedTime'],
                            xLabelAngle: 60,
                            barColors:function (row, series, type) {
                                switch (row.label){
                                    case "vlhommeau" : return "#3366ff";
                                    case "sho" : return "#deac34";
                                    case "MaximeGammaitoni" : return "#779914";
                                    case "Jérôme" : return "#10ff00";
                                    case "ebourgin" : return "#00ffcc";
                                    case "tcoince" : return "#ffff00";
                                    case "Marielle" : return "#993333";
                                    case "Anthony C" : return "#ffcc99";
                                    case "mlagarde" : return "#ff0066";
                                    case "lotfi" : return "#ff6600";
                                    case "Adrien" : return "#cc33ff";
                                    case "cgr" : return "#ccccff";
                                    case "maxleroy" : return "#c0ffb1";
                                    case "MathieuH" : return "#ffd9fa";
                                    case "rwellens" : return "#faffc5";
                                }
                            }
                        });

                        $("#buttonEmail").css("display","block");
                    }

                }
            }
        }
    });
} // call à l'API

myApp2.initLoader = function (){
    $("#bluecircle").percircle();
} //déclaration loader

myApp2.showPage = function() {
    $("#loader").css("display", "block");
} // affichage du loader

myApp2.clearData = function() {
    myApp2.table.clear().draw();
    myApp2.tableBar=[];
    myApp2.tableLog=[];
    myApp2.tableBar=[];
    $("#morris-bar-H-chart").empty();
    $("#textModal").empty();
} // lorsque l'on relance un appel on vide au préalable tableau et graphiques et div

myApp2.updateValues = function () {
    this.board = $('#listeBoard').val();
    this.fromdate = $("#input1").val();
    this.todate = $("#input2").val();
} // instensiation des variables à l'appel API

myApp2.sendMail = function (){
    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data:{
            action : 'sendEmail',
            board : myApp2.board,
            loggedTime : JSON.stringify(myApp2.tableBar),
            fromdate : myApp2.fromdate,
            todate : myApp2.todate
        },
        success: function (data) {
            $("#textModal").empty();
            var span = document.getElementsByClassName("close")[0];
            var modal = document.getElementById("myModal");
            $("#textModal").append('Mail send !')
            $("#myModal").css("display","block")
            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                $("#myModal").css("display","none")
            }
            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    $("#myModal").css("display","none")
                }
            }
        }
    });
}