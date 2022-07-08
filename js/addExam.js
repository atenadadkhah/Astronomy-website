 // define whether all values are true
    let showValid={
        name:false,
        score:true,
        num:true,
        examExist:true,
    }
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
        addQuestion($("input[name='question_num']"))
        $("input[name='question_num']").change(function () {
            addQuestion(this)
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
        $("form").submit(function (){
            return validate_form()
        })
    })
    function addQuestion($input) {
        $($input).parent().children("small").remove()
        $(".exam-questions").html("")
        $(".pagination").html("")
        let value = $($input).val().trim();
        if (!isNaN(value)) {
            if (value > 20 || value < 1) {
                value = 5;
                $($input).after("<small class='text-danger mistake'>تعداد سوالات نمی تواند بیش از 20 و کمتر از 1 باشد.</small>")
                showValid.num = false
            } else {
                $($input).next("small").remove()
                value = $($input).val()
                showValid.num = true;
            }
        } else value = 5;
        $.ajax({
            url: '../checks/questionValues.php',
            type: 'post',
            data: {
                question_num: $("[name='question_num']").val()
            },
            success:function (res){
                $(".exam-questions").html(res)
            },
            error:function (){
                alert("error")
            }
        })
}
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
    // validation
    $("input[name='each_score']").blur(function (){
        let value=$(this).val();
        $(this).parent().children("small").remove()
        if (value <1 || value>10){
            $(this).after("<small class='text-danger mistake'>امتیاز هر سوال نمی تواند بیش از 10 و کمتر از 1 باشد.</small>")
            showValid.score=false;
        }else{
            showValid.score=true;
        }
    })
    const examNamePattern=/^([\u0600-\u06FFa-z0-9A-Z?!][ ]?){2,80}$/
    $("input[name='exam_name']").blur(function (){
        let value=$(this).val();
        let thisElem=this;
        $(this).parent().children("small").remove()
        if (value.length !=0){
            if (!examNamePattern.test(value)){
                $(this).after("<small class='text-danger mistake'>لطفا یک نام صحیح انتخاب کنید.</small>")
                showValid.name=false
            }else{
                showValid.name=true
            }
        }else{
            $(this).after("<small class='text-danger mistake'>نام نمی تواند خالی باشد.</small>")
            showValid.name=false
        }
        $.ajax({
            url: '../checks/examExist.php',
            type: 'post',
            data: {
                examName:$(this).val(),
            },
            success: function (res) {
                if (res=="true"){
                    showValid['examExist']=false
                    if ($(thisElem).parent().children(".examExist").length != 1) {
                        $(thisElem).after("<small class='text-danger examExist'>این نام آزمون درحال حاضر موجود است.</small>")
                    }
                }
                else {
                    $(this).parent().children(".examExist").remove()
                    showValid['examExist']=true
                }
            },
            error: function () {
                alert("err")
            },
            async:false,
        })
    })
    // check at first the values when no action happen (like blur or change)
    if (examNamePattern.test($("[name='exam_name']").val())) showValid.name=true;
    else showValid.name=false;
    if ($("[name='question_num']").val().length !=0 && $("[name='question_num']").val() >0 && $("[name='question_num']").val() <=20) showValid.num=true;
    else showValid.num=false;
    if ($("[name='each_score']").val().length !=0 && $("[name='each_score']").val() >0 && $("[name='each_score']").val() <=10) showValid.score=true;
    else showValid.score=false;
    function validate_form ( ) {
        valid = true;
        let full=true
        $(".change-settings input").each(function (index,element){
            switch ($(this).val()){
                case "":
                    full=false
                    break
            }
        })
        let boolShowValid=[]
        for (i in showValid){
            boolShowValid.push(showValid[i])
        }
        if (boolShowValid.every((value)=> value ===true) !== true   || full !==true){
            valid = false;
        }
        return valid;
    }
