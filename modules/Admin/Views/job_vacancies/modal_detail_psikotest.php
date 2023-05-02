<style>
    .modal-body button.close {
        position: absolute;
        right: -20px;
        top: -20px;
        width: 30px;
        height: 30px;
        background-color: white;
        border: 2px solid #000;
        border-radius: 100%;
        z-index: 100;
    }
</style>
<div class="modal-body p-2">
<button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
<?php if($data->respond_psikotest && $categories) : ?>
    <?php foreach($categories as $category_name => $questions) : ?>
        <h5 class="font-weight-bolder">Kategori Soal : <?= $category_name ?></h5>
        <hr>
        <?php $responds = json_decode($data->respond_psikotest, true); ?>
        <?php foreach($questions as $index => $question) : ?>
        <?php 
            $userAnswers = isset($responds['category-' . $question->id_kategori]) ? $responds['category-' . $question->id_kategori] : NULL;
            $selectedOption = $userAnswers && isset($userAnswers['question-' . $question->id]) ? explode('-index-', $userAnswers['question-' . $question->id])[0] : NULL;
            ?>
            <div class="card <?= !$selectedOption ? 'border-warning' : '' ?> shadow-none question question-<?= $index ?>">
                <div class="card-body">
                    <div class="d-flex w-100">
                        <h5 class="font-weight-bolder mr-2"><?= $index + 1 ?>. </h5>
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
                                <div class="ml-1">
                                    <?php if($file_preview ) : ?>
                                        <?= $file_preview ?>
                                    <?php endif ?>
                                    <h5 class="font-weight-bolder"><?= $question->pertanyaan ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6 class="font-weight-bolder ml-2 mt-2 mb-1">Pilihan :</h6>
                    <?php
                        $alphabet = range('A', 'Z');
                        $options = json_decode($question->options);
                    ?>
                    <?php foreach($options as $i => $option) : ?>
                        <?php ?>
                        <div class="row opsi-<?= $i ?>">
                            <div class="col-12 pl-3 option-<?= $i ?> opsi-<?= $index . '-' . $i ?>">
                                <div class="card mb-1 <?= $alphabet[$i] == $question->jawaban ? 'border-success' : ($selectedOption && $selectedOption == $alphabet[$i] && $selectedOption !== $question->jawaban ? 'border-danger' : '') ?> pt-1 pl-1">
                                    <div class="card-body pt-0 px-0">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <label class="alpha-radio">
                                                    <input type="radio" disabled="disabled" <?= $selectedOption && $selectedOption == $alphabet[$i] ? 'checked' : '' ?> name="answers[<?= $index ?>]" value="<?= $alphabet[$i] ?>"/>
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
    <?php endforeach ?>
<?php else : ?>
    <div class="col-12 text-center">
        <img class="mt-2" style="width:15%" src="<?= base_url('images/illustrations/empty.png') ?>" alt="empty state">
    </div>
<?php endif ?>
</div>