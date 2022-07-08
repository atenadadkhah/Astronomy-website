$(function () {
    $("span.fa-angle-left").click(function () {
        $(".change-settings").toggleClass("hide-setting", 1000, "linear")
        $(window).resize(function (){
            if ($(window).width() >= 975){
                if ($(".change-settings").hasClass("hide-setting")){
                    closeSetting()
                }else{
                    openSetting()
                }
            }else{
                $(".change-settings").removeClass("hide-setting")
                openSetting()
            }
        })
        if ($(window).width() >= 975) {
            if ($(".change-settings").hasClass("hide-setting")) {
                closeSetting()
            } else {
                openSetting()
            }
        }
    })
    $(window).scroll(function (){
        if ($(window).width() >= 975){
            let scrollTop=$(window).scrollTop()
            if (($("body").height() - 760) >= scrollTop) {
                if (scrollTop > 650) {
                    $(".change-settings").css("top", scrollTop - 580)
                } else {
                    $(".change-settings").css("top", 0)
                }
            }else $(".change-settings").css("top",$("body").height() - 1400 )

        }else{
            $(".change-settings").css("top", 0)
        }
    })
    $(window).resize(function (){
        if ($(window).width() < 975){
            $(".change-settings").css("top", 0)
        }
    })
})
// this function runs when the setting button click to close
function closeSetting(){
    const element=$(".exam-questions")
    element.parent().removeClass("col-lg-7").addClass("col-lg-10")
    element.css("right",'10%')
    $(".action-btn").css("right",'10%')
}
// this function runs when the setting button click to open
function openSetting(){
    const element=$(".exam-questions")
    element.parent().removeClass("col-lg-10").addClass("col-lg-7")
    element.css("right",0)
    $(".action-btn").css("right",0)
}