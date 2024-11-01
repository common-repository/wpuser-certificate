jQuery(document).ready(function($){


    var sendusersdata = document.getElementById("sendusersdata"),
        usersresult = document.getElementById("usersresult");

    if (sendusersdata != null) {

        sendusersdata.onclick = async function () {
            // AJAX url
            var ajax_url = plugin_ajax_object.ajax_url;

            let queryString = window.location.search;
            let urlParams = new URLSearchParams(queryString);
            let courseid = urlParams.get('courseid')
            
            studentsArray[0][2] = parseInt(courseid);
        
            console.log(studentsArray)
            // Fetch All records (AJAX request without parameter)
            var data = {
                'action': 'getstudentsdata',
                'studentsData': studentsArray
            };

            alert("taha")
        
            $.ajax({
                url: ajax_url,
                type: 'post',
                data: data,
                dataType: 'json',
                success: function(response){
                    // Add new rows to table
                    alert(response);

                    if (response != "empty") {
                        usersresult.innerHTML = "Yours Users Can Now Log In";
                    }
                    
                    console.log(response)
                }, error: function (err) {
                    console.log(err)
                }
            });
        }
        
    }
    
  
    
});