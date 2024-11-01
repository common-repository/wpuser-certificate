

// var sendusersdata = document.getElementById("sendusersdata");

sendusersdata.onclick = function () {
    $.post("/", {suggest: 'txt'}, function(result){
        alert(result)
    }, 'json');
}