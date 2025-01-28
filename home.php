
<style>
    #top-header{
        height:70vh;
        
    }
    #top-header *{
        text-shadow: 3px 2px #000000b0;
    }
    #top-header .title{
        font-size:5em;
        text-shadow:4px 4px #000000f5;
    }
    #top-header:before{
        content:'';
        position:absolute;
        height:80vh;
        width: calc(100%);
        top:0;
        left:0;
        background-image:url('<?php echo validate_image($_settings->info('cover')) ?>') !important;
        background-size:cover;
        background-repeat:no-repeat;
        background-position:center center;
        z-index: 0;
        filter:brightness(.95)
    }
    .company-name {
        font-size: 1.35rem;
        font-variant: all-petite-caps;
        font-family: monospace;
        font-weight: 600;
        color: #b5b5b5;
    }
    img.company-logo {
        transition: transform .02s ease-in;
    }
    img.company-logo:hover {
        transform: scale(1.02);
    }
</style>
<header id="top-header" class="d-flex justify-content-center align-items-end py-3 px-5">
    <div class="position-relative" style="z-index: 1;">
    <div class="mb-5 pb-5">
        <h1 class='text-center title'>Welcome to <?php echo $_settings->info('name') ?></h1>
    </div>
    </div>
</header>
<section class="py-4">
    <div class="container">
        <?php echo is_file('welcome_message.html') ? file_get_contents('welcome_message.html') : "Welcome Content is Empty" ?>
    </div>
</section>
<section class="py-4 bg-gradient-dark">
    <div class="container">
        <h2 class="text-center">Our Partners</h2>
        <center><hr class="border-primary" width="50px"></center>
        <div class="col-12">
            <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-xl-3 gx-2 gy-2 justify-content-center">
                <?php 
                $company = $conn->query("SELECT * FROM `company_list` where status = 1 order by `name` asc");
                while($row = $company->fetch_assoc()):
                ?>
                <div class="col">
                    <div class="m-2 w-100 d-flex flex-column justify-content-center align-items-center h-100 overflow-hidden">
                        <img src="<?php echo validate_image(is_file(base_app."uploads/company_logos/{$row['id']}.png") ? "uploads/company_logos/{$row['id']}.png?v=".(strtotime($row['date_updated'])) : $_settings->info('logo') ) ?>" alt="<?php echo $row['name'] ?> logo" class="company-logo mb-2">
                        <div class="company-name text-center"><?php echo $row['name'] ?></div>
                    </div>
                </div> 
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</section>