<?php
//admin profile pic
if (isset($_SESSION['adminInfo'])){?>
    <section class="avatar-section float-left ml-5 mt-3 mb-1">
        <a href="">
            <span class="userName ml-1" style="font-weight: 600;font-family: 'vazir-regular';"><?php echo $_SESSION['adminInfo']['userName'] ?> | Admin</span>
            <img src="<?php echo $_SESSION['adminInfo']['avatar'] ?>" alt="Avatar-admin" class="avatar">
        </a>
    </section>
<?php }?>