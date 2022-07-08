<?php //footer component ?>
<?php function footer($directory=0){ ?>
    <?php $directory==0 ? $dir="../" : $dir=""; ?>
<footer>
    <div class="container-fluid p-3">
        <h5 class="brand-name text-center "><a href=""><img src="<?php echo $dir ?>icons/logo.png" alt="logo" width="60"></a></h5>
        <br>
        <div class="row justify-content-center">
                <a href="<?php echo $dir."about.php"?>" class="text-center text-center mx-3">درباره ما</a>
        </div>
    </div>
</footer>
<?php } ?>