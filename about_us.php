
<header id="top-header" class="d-flex justify-content-center align-items-end py-3 px-5">
    <div class="position-relative" style="z-index: 1;">
    <div class="mb-5 pb-5">
        <h1 class='text-center title'>About Us</h1>
    </div>
    </div>
</header>
<section class="py-4">
    <div class="container">
        <?php echo  is_file('about_us.html') ? file_get_contents('about_us.html') : "About Us Content is Empty" ?>
    </div>
</section>