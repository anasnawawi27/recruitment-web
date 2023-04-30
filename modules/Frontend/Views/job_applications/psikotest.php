<?php $this->extend('layout/psikotest') ?>
<?php $this->section('content')?>
<?php if($data->status == 'applied') : ?>
    <?php if(count($questions) > 0) : ?>
        <div class="row section-test">
            <div class="col-md-4 sticky-preview">
                <div class="container-preview">
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-danger mb-2" role="alert">
                                <strong>Notest : </strong> Submit jawaban sebelum waktu habis !
                            </div>
                            <h5 class="font-weight-bold">Preview Jawaban</h5>
                            <?php foreach($questions as $key => $value) : ?>
                                <p class="mt-2">Kategori <?= $key ?></p>
                                <hr>
                                <div class="row mb-3">
                                <?php if(count($questions[$key]) > 0) : ?>
                                    <?php foreach($questions[$key] as $index => $question) : ?>
                                        <div class="col-3 d-flex pr-0 mt-1 w-100">
                                            <div style="width:28%"><?= ++$index ?>.</div>
                                            <div style="width:72%"><div class="circle category-<?= $question->id_kategori ?>-question-<?= $question->id ?>"></div></div>
                                        </div>
                                    <?php endforeach ?>
                                <?php else : ?>
                                    <div class="w-100 text-center">
                                        <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
                                    </div>
                                <?php endif ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <?php foreach($questions as $key => $value) : ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="font-weight-bold">Kategori : <?= $key ?></h5>
                            <?php if(count($questions[$key]) > 0) : ?>
                                <?php foreach($questions[$key] as $index => $question) : ?> 
                                    <div class="card shadow-none mb-0">
                                        <div class="card-body pb-0">
                                            <div class="d-flex w-100">
                                                <h5 class="font-weight-bolder mr-2"><?= ++$index ?>. </h5>
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex">
                                                        <?php
                                                        $cld = new \Cloudinary\Cloudinary(CLD_CONFIG);
                                                            if ($question->gambar) {
                                                                $image = $cld->image($question->gambar);
                                                                $file_preview = '<img class="img-thumbnail mb-2" style="width:200px; height:200px; object-fit: contain" src="' . $image . '">';
                                                            } else {
                                                                $file_preview = '';
                                                            }
                                                        ?>
                                                        <div class="ml-0">
                                                            <?php if($file_preview ) : ?>
                                                                <?= $file_preview ?>
                                                            <?php endif ?>
                                                            <h5 class="font-weight-bolder"><?= $question->pertanyaan ?></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                $alphabet = range('A', 'Z');
                                                $options = json_decode($question->options)
                                            ?>
                                            <?php foreach($options as $i => $option) : ?>
                                                <div class="row opsi-<?= $i ?>">
                                                    <div class="col-12 pl-3">
                                                        <div class="card mb-0">
                                                            <div class="card-body pb-1 pt-0 px-0">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <label class="alpha-radio">
                                                                            <input type="radio" id="category-<?= $question->id_kategori ?>-question-<?= $question->id ?>-index-<?= $i ?>" class="choose option-<?= $question->id_kategori ?>" data-index="<?= $i ?>" data-category-id="<?= $question->id_kategori ?>" data-question-id="<?= $question->id ?>" name="answers[category_<?= $question->id_kategori ?>][question_<?= $question->id ?>]" value="<?= $alphabet[$i] ?>"/>
                                                                            <span><?= $alphabet[$i] ?></span>
                                                                        </label>
                                                                    </div>
                                                                    <?php
                                                                        if (isset($option->gambar_id)) {
                                                                            $image = $cld->image($option->gambar_id);
                                                                            $preview = '<img class="img-thumbnail mb-2" style="width:200px; height:200px; object-fit: contain" src="' . $image . '">';                                        
                                                                        } else {
                                                                            $preview = '';
                                                                        }
                                                                    ?>
                                                                    <div class="d-flex justify-content-between w-100">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="ml-1">
                                                                                <?php if($preview ) : ?>
                                                                                    <?= $preview ?>
                                                                                <?php endif ?>
                                                                                <h6><?= $option->opsi ?></h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php else : ?>
                                <div class="w-100 text-center">
                                    <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary round btn-glow btn-block mb-3 submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="w-100 text-center">
            <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
        </div>
    <?php endif ?>
<?php endif ?>
<?php $this->endSection(); ?>

<?php $this->section('plugin_css'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.10/css/perfect-scrollbar.css">
<?php $this->endSection(); ?>
<?php $this->section('custom_css'); ?>
<style>
    .container-preview {
        width: 100%;
        height: 80vh;
        position: relative;
        overflow: auto;
    }
    .border-top{
        border-top: 1px solid #e5e5e5!important;
    }

    label.alpha-radio input[type="radio"] {
        width: 30px;
        height: 30px;
        border-radius: 15px;
        border: 2px solid #1FBED6;
        background-color: white;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    label.alpha-radio input[type="radio"]:focus {
        outline: none; 
    }

    label.alpha-radio input[type="radio"]:checked {
        background-color: #1FBED6;
    }

    label.alpha-radio input[type="radio"]:checked ~ span:first-of-type {
        color: white;
    }

    label.alpha-radio span:first-of-type {
        position: relative;
        left: -20px;
        top: 0;
        font-size: 15px;
        color: #1FBED6;
    }

    label.alpha-radio{
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    label.alpha-radio span {
        position: relative;
        top: -12px;
    }
</style>
<?php $this->endSection(); ?>

<?php $this->section('plugin_js'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.10/js/perfect-scrollbar.jquery.js"></script>
<?php $this->endSection(); ?>

<?php $this->section('custom_js'); ?>
<script>
    let preview = {};
    if(localStorage.getItem('times-up')){
        set_status_failed();
    }

    if(localStorage.getItem('psikotest-start-<?= $data->posisi ?>')){

        let countDownDate = Number(localStorage.getItem('countdown-date'));

        let myfunc = setInterval(function() {
        let now = new Date().getTime();
        let timeleft = countDownDate - now;

        let days = Math.floor(timeleft / (1000 * 60 * 60 * 24)).toString();
        let hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString();
        let minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60)).toString();
        let seconds = Math.floor((timeleft % (1000 * 60)) / 1000).toString();

        let counterLabel = `${days.length > 1 ? days : '0' + days }:${hours.length > 1 ? hours : '0' + hours}:${minutes.length > 1 ? minutes : '0' + minutes}:${seconds.length > 1 ? seconds : '0' + seconds}`;
        $('.counter').html('&nbsp; ' + counterLabel);

        if (timeleft < 0) {
            clearInterval(myfunc);
            $('.counter').html('&nbsp; Waktu Habis!');
            localStorage.setItem('times-up', 'TRUE');
            set_status_failed()
            $('.section-test').html('<h1 class="font-weight-bold mx-auto my-4">Waktu Test Psikotest Habis !</h1>')
        }
            
        
    }, 1000);

    function set_status_failed(){
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: siteUrl + '<?= route_to('set_status_failed', $data->id) ?>',
            success: function(res){
                if(re.status == 'success'){
                    localStorage.removeItem('psikotest-start-<?= $data->posisi ?>'); 
                    localStorage.removeItem('countdown-date'); 
                    localStorage.removeItem('times-up');
                    localStorage.removeItem('preview'); 
                }
            }
        })
    }

    $(document).ready(function(){
        if(localStorage.getItem('preview')){
            preview = JSON.parse(localStorage.getItem('preview'));
            displayPreview();
        }

        $(".sticky-preview").scrollFix({
            side: "top",
            topPosition: 100,
        });

        $('.container-preview').perfectScrollbar();
    })

    $('.choose').on('click', function(){
        let value = $(this).val();
        let index = $(this).data('index');
        let categoryId = $(this).data('category-id');
        let questionId = $(this).data('question-id');

        if(localStorage.getItem('preview')){
            preview = JSON.parse(localStorage.getItem('preview'));
        }
        
        if('category-' + categoryId in preview){
            preview['category-' + categoryId]['question-' + questionId] = value + '-index-' + index;
        } else {
            preview['category-' + categoryId] = {};
            preview['category-' + categoryId]['question-' + questionId] = value + '-index-' + index;
        }
        console.log(preview)
        localStorage.setItem('preview', JSON.stringify(preview))
        displayPreview();
    })

    function displayPreview(){
        for (const category in preview) {
            for (const question in preview[category]) {
                let split = preview[category][question].split('-index-');
                let value = split[0];
                let index = split[1];

                $(`.${category}-${question}`).html(value)
                $(`#${category}-${question}-index-${index}`).prop('checked', true);
            }
        }
    }
    } else {
        $('.section-test').html('<h1 class="font-weight-bold mx-auto my-4">Sesi Psikotest Belum dimulai !</h1>')
    }

    $('.submit').on('click', function(){
        $submit = $(this)
        $submit.attr("disabled", "disabled");
        $submit.html(loadingButtonText);

        if(localStorage.getItem('preview')){
            preview = JSON.parse(localStorage.getItem('preview'));
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: siteUrl + '<?= route_to('submit_psikotest') ?>',
            data: {
                id: '<?= $data->id ?>',
                data: preview,
            },
            success: function(res){
                if(res.status == 'success'){
                    localStorage.removeItem('psikotest-start-<?= $data->posisi ?>'); 
                    localStorage.removeItem('countdown-date'); 
                    localStorage.removeItem('times-up'); 
                    localStorage.removeItem('preview'); 

                    $submit.removeAttr('disabled');
                    $submit.html('Submit');

                    if(res.redirect){
                        window.location.href = res.redirect
                    }
                } else {
                    errorMessage(res.message);
                }
            }
        })
    })
    
</script>
<?php $this->endSection(); ?>
