$('#open').click(function () {
    if($('#open').hasClass('closed')){
        document.getElementById("mySidenav").style.width = "280px";
        document.getElementById("main").style.marginLeft = "280px";
        $('#open').removeClass('closed');
        $('.sidenav li').delay(200).fadeIn();
    } else{
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
        $('#open').addClass('closed');
        $('.sidenav li').hide();
    }
});

$('.select').select2();
