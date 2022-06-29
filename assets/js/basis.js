$(document).ready(function() {
    $("#headermenubutton").click(function() {
        $('#headermenu').toggleClass('activemenu');
        setTimeout(() => {
            $('.item').toggleClass('active')
        }, 500);
    })

    // Mainpage functions
    $('.mainpage').ready(function() {
        
    })

    // Login page functions
    $('.loginpage').ready(function() {
        
    })
})