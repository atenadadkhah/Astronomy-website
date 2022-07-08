// noinspection InfiniteLoopJS
$(function (){
    $(".toTop a svg").remove()
    $(window).scroll(function(){
        if ($(this).scrollTop()>($(window).height()/2)){
            $(".toTop").removeClass("d-none");
            $(".toTop").addClass("d-block")
        }else{
            $(".toTop").removeClass("d-block");
            $(".toTop").addClass("d-none")
        }
    })
    if ($(window).scrollTop()>($(window).height()/2)){
        $(".toTop").removeClass("d-none");
        $(".toTop").addClass("d-block")
    }else{
        $(".toTop").removeClass("d-block");
        $(".toTop").addClass("d-none")
    }
    $("h1.title-site,h1.title-name").fitText(.7,{minFontSize: '35px',maxFontSize: '90px'})
    $("h2").fitText(1,{maxFontSize: '40px'})
    $("h4").fitText(1.4,{minFontSize:'22px'})
    $("h3").fitText(1,{maxFontSize:'70px'})
    $("h4.info").fitText(0.8,{minFontSize:'50px',maxFontSize:'80px'})
    $("p").fitText(2.4,{minFontSize: '16px',maxFontSize:'25px'})
    $("p.p-hero").fitText(2.2,{maxFontSize: '22px',minFontSize:'16px'})
    $(".end-info p").fitText(4,{minFontSize:'15px'})
    new WOW().init();
})

function openRightMenu() {
    document.getElementById("rightMenu").style.display = "block";
}

function closeRightMenu() {
    document.getElementById("rightMenu").style.display = "none";
}
