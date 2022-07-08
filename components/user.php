<?php
//user profile pic
if (isset($_SESSION['userInfo'])){?>
    <section class="avatar-section float-left ml-4 mt-3 mb-1">
        <a href="/project/profile/profile.php">
            <span class="userName ml-1" style="font-weight: 600;font-family: 'vazir-regular';"><?php echo getUserById($_SESSION['userInfo']['id'])['userName'] ?></span>
            <img src="<?php echo getUserById($_SESSION['userInfo']['id'])['avatar'] ?>" alt="Avatar-user" class="avatar">
        </a>
    </section>
<?php }?>
