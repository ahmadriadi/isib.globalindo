$(document).ready(function() {

    $("#username").focus();
    
    $("button#submit").click(function() {
        $("#span-username").html("");
        $("#span-password").html("");
        
        if ($("#username").val() != "" && $("#password").val() != "") {
            $("#login-msg").html("<p class=\"glyphicons restart\"><i></i>Verifying ...</p>");
            var dts = 'username=' + $("#username").val() + '&password=' + $("#password").val();
            var url = ROOT.site_url + "/login/verification"
            $.ajax({
                type: "POST",
                url: url,
                data: dts,
                cache: false,
                success: function(data) {
                    //alert(data.success+'-'+data.mesg+'-'+data.redir);
                    if(data.success=='yes') {
                        alert("access granted");
                        //alert(data.mesg);
                        window.location.href = data.redir;
                    } else {
                        $("#login-msg").html("<p class=\"glyphicons restart\"><i></i>"+data.mesg+" ...</p>");
                        alert("access denied");
                    }
                },
                error: function(xhr, ajaxOptions, errorThrown) {
                    alert('request failed \n' + xhr.responseText + '\n' + errorThrown);
                }
            })
        } else {
            if ($("#username").val() == "")
                $("#span-username").html("Specify Username");
            if ($("#password").val() == "")
                $("#span-password").html("Specify Password");
        }
        
        return false; // To prevent form submission
    })
});

