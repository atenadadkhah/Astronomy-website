$(function (){
    //script for long about contents to show less or more
        let aboutValue=$(".about").text()
        if (aboutValue.length > 150){
            $(".about").text(aboutValue.substring(0,180) + "...")
            $(".show-more").click(function (){
                $(this).toggleClass("more");
                if ($(this).hasClass("more")){
                    $(".about").text(aboutValue);
                    $(".show-more").text("کمتر")
                }else{
                    $(".about").text(aboutValue.substring(0,180) + "...");
                    $(".show-more").text("بیشتر")
                }

            })
        }
    $(".img-avatar").click(function (){
        $(".img-avatar").removeClass("active-avatar");
        $(this).addClass("active-avatar");
        $('[name="avatar"]').val($(this).children("img").attr("src"));
    })
})
let chatTo=false;
$(".chat").animate({ scrollTop: $(".chat").prop('scrollHeight') }, "fast");
if ($("[data-query='form-chat']").length==1){
    $(".show-message").html("")
    $.ajax({
        url: "../checks/processMessage.php",
        type: "post",
        data: {
            contact_id:$(".chat-to").val(),
        },
        success: function(response) {
            $(".chat-thread").html(response)
            $("button[value='"+$(".chat-to").val()+"']").css("background","#02193e");
            $(".chat").animate({ scrollTop: $(".chat").prop('scrollHeight') }, "fast");
            chatTo=$(".chat-to").val()
        },
        error: function(xhr) {
            alert("error");
        }
    });
}
$(".contact").click(function(){
    const thisElem=$(this)
    $(".show-message").html("");
    $(".contact-button").removeAttr("style");
    $.ajax({
        url: "../checks/processMessage.php",
        type: "post",
        data: {
            contact_id: thisElem.val(),
        },
        success: function(response) {
            $(".chat-thread").html(response)
            $(".chat-to").val(thisElem.val());
            $("button[value='"+thisElem.val()+"']").css("background","#02193e");
            $(".chat").animate({ scrollTop: $(".chat").prop('scrollHeight') }, "fast");
            chatTo=thisElem.val()
        },
        error: function(xhr) {
            alert("error");
        }
    });
})
$('[data-query="form-chat"]').submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = $(this);
    // var actionUrl = form.attr('action');
    if (chatTo==$("[name='contact_id']").val()){
        $.ajax({
            type: "POST",
            url: "../checks/ajax-contact.php",
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
                $(".chat-thread").append(data)// show response from the php script.
                $("[name='message']").val("");
                $(".chat").animate({ scrollTop: $(".chat").prop('scrollHeight') }, "fast");
            }
        });
    }
});