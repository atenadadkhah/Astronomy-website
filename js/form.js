let isValid=true
let showValid={}
$(function (){
   // return false or true when submit => depends on the values are correct not
    $('form.form-yes').submit(function (){
        return validate_form()
    })
    // prevent from submitting with enter key
    $("form.form-yes").keypress(function (ev){
        if (ev.which == 13){
            ev.preventDefault()
        }
    })
// variable to check every input values are true
    $("[data-validation]").each(function (index,element){
        if ($(element).attr("data-validation") != "age" && $(element).attr("data-validation") != "city"){
            if ($(element).val() != ""){
                if ($(element).attr("data-validation")=="userName"){
                    let userExist={
                        userExist:true
                    }
                    Object.assign(showValid, userExist)
                }
                lastChild={
                    [$(element).attr("data-validation")]:true
                }
            }
            else{
                if ($(element).attr("data-validation")=="userName"){
                    let userExist={
                        userExist:false
                    }
                    Object.assign(showValid, userExist)
                }
                lastChild={
                    [$(element).attr("data-validation")]:false
                }
            }
        }else{
            lastChild={
                [$(element).attr("data-validation")]:true
            }
        }
        Object.assign(showValid,lastChild )
    })
})


function checkInputs(item,validation,typeV,msg){
    let value=item.value.trim()
    const checkValid=validation
    if (checkValid.test(value) !== true){
        if ($(item).parent().children(".mistake").length != 1){
            $(item).parent().children("small").remove()
            $(item).after("<small class='text-danger mistake'>"+msg+"</small>")
            showValid[typeV]=false
        }
        return false
    }else{
        $(item).parent().children(".mistake").remove()
        showValid[typeV]=true
        return true
    }
}
// ajax function to check if the user exists in database fo sign in
function checkUserExist(item,mode="normal"){
    let urlAddress="";
    if (mode == "normal"){
        urlAddress='../checks/userExist.php'
    }
    else if (mode == "update"){
        urlAddress='../checks/userExistUpdate.php'
    }
    $.ajax({
        url: urlAddress,
        type: 'post',
        data: {
            userName:$(item).val(),
        },
        success: function (res) {
            if (res=="true"){
                showValid['userExist']=false
                if ($(item).parent().children(".userExist").length != 1) {
                    $(item).after("<small class='text-danger userExist'>این نام کاربری درحال حاضر موجود است</small>")
                }
            }
            else {
                $(item).parent().children(".userExist").remove()
                showValid['userExist']=true
            }
        },
        error: function () {
            alert("err")
        },
        async:false,
    })
}
function validName(item){
    checkInputs(item,/^([\u0600-\u06FFa-zA-Z][ ]?){2,40}$/,"name","نام وارد شده اشتباه است")
}
function validLastName(item){
    checkInputs(item,/^([\u0600-\u06FFa-zA-Z][ ]?){2,40}$/,"lastName","نام خانوادگی وارد شده اشتباه است")
}
function validUserName(item){
    checkInputs(item,/^(?=.*[\u0600-\u06FFA-Za-z])[\u0600-\u06FFA-Za-z0-9\-_.]{1,20}$/g,"userName","نام کاربری وارد شده صحیح نمی باشد")
}
function validPassword(item){
    checkInputs(item,/^(?=.*[A-Za-z])(?=.*\d)[A-za-z\d@^#$!%*?&]{8,100}$/g,"password","رمز عبور باید بیش از 7 کاراکتر انگلیسی باشد و حداقل شامل یک حرف  و یک عدد باشد")
}
function validationAge(item){
    let value=item.value.trim()
   if (value.length != 0){
       checkInputs(item,/^[1-9][0-9]$/g,"age","سن وارد شده اشتباه است")
   }else{
       checkInputs(item, /.*/g, "age", "سن وارد شده اشتباه است")
   }
}

function validationCity(item){
    let value=item.value.trim()
    if (value.length != 0) {
        checkInputs(item, /^([\u0600-\u06FFa-zA-Z][ ]?){2,40}$/, "city", "نام شهر اشتباه است")
    }
    else{
        checkInputs(item, /.*/g, "city", "نام شهر اشتباه است")
    }
}
function validation(item){
    let value=item.value.trim()
    let attr=$(item).attr("data-validation")
    let attrArr=attr.split(",")
    let flag=true;
    for (i in attrArr){
        if (flag){
            switch (attrArr[i]){
                case "name":
                    flag=validName(item)
                    break
                case "lastName":
                    flag=validLastName(item);
                    break;
                case "userName":
                    flag=validUserName(item)
                    break
                case "password":
                    flag=validPassword(item)
                    break
                case "age":
                    flag=validationAge(item)
                    break
                case "city":
                    flag=validationCity(item)
                    break
            }
        }else{
           break;
        }
    }

}
// if each values are wrong => return false
function validate_form ( ) {
    valid = true;
    let full=true
    $("[data-validation]").each(function (index,element){
        if ($(element).attr("data-validation") != "age" && $(element).attr("data-validation") != "city"){
            switch ($(this).val()){
                case "":
                    full=false
                    break
            }
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
