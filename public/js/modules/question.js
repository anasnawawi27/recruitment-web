let indexQuestion = 1;

$("body").on("click", ".add-option", function () {
  let index = $(this).data("index");
  let optionLength = $(".option-" + index).length;
  let content = `<div class="col-12 pl-3 option-${index} opsi-${index}-${optionLength}">
                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <label class="alpha-radio">
                                            <input type="radio" name="answers[${index}]" value="${numToAlpha(
    optionLength
  )}"/>
                                            <span>${numToAlpha(
                                              optionLength
                                            )}</span>
                                        </label>
                                    </div>
                                    <div class="d-flex justify-content-between w-100">
                                        <div class="d-flex justify-content-between">
                                            <div class="form-group mb-0">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                                    <div>
                                                        <span class="btn btn-raised btn-sm btn-outline-secondary btn-file cursor-pointer">
                                                            <span class="fileinput-new">
                                                                <i class="la la-image"></i>
                                                            </span>
                                                            <span class="fileinput-exists">
                                                                <i class="la la-image"></i>
                                                            </span>
                                                            <input class="upload-file" type="file" name="image_options[${index}][${optionLength}]" data-param="2" accept="image/*">
                                                        </span>
                                                        <input type="hidden" name="delete_image" value="">
                                                        <a href="#" class="btn btn-raised btn-sm btn-danger fileinput-exists delete_image" data-dismiss="fileinput">
                                                            <i class="la la-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-1">
                                                <textarea cols="100" name="options[${index}][${optionLength}]" class="form-control" placeholder="Jawaban"></textarea>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)" class="text-danger ml-2 delete-option" data-index="${index}" data-length="${optionLength}">
                                            <i class="la la-close"></i>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>`;
  $(".opsi-" + index).append(content);
});

$(".add-question").on("click", function () {
  let content = `<div class="card shadow-none question question-${indexQuestion}">
                        <div class="card-body">
                            <div class="d-flex w-100">
                                <h5 class="font-weight-bolder mr-2">${
                                  indexQuestion + 1
                                }. </h5>
                                <div class="d-flex justify-content-between w-100">
                                    <div class="d-flex">
                                        <div class="form-group">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                                <div>
                                                    <span class="btn btn-raised btn-sm btn-outline-secondary btn-file cursor-pointer">
                                                        <span class="fileinput-new">
                                                            <i class="la la-image"></i>
                                                        </span>
                                                        <span class="fileinput-exists">
                                                            <i class="la la-image"></i>
                                                        </span>
                                                        <input class="upload-file" type="file" name="image_questions[${indexQuestion}]" data-param="2" accept="image/*, application/pdf">
                                                    </span>
                                                    <input type="hidden" name="delete_image" value="">
                                                    <a href="#" class="btn btn-raised btn-sm btn-danger fileinput-exists delete_image" data-dismiss="fileinput">
                                                        <i class="la la-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-1">
                                            <textarea cols="115" name="questions[${indexQuestion}]" class="form-control" placeholder="Tambah Pertanyaan"></textarea>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" class="text-danger delete-question ml-2" data-index="${indexQuestion}">
                                        <i class="la la-close"></i>
                                    </a>
                                </div>
                            </div>
                            <h6 class="font-weight-bolder ml-2 mt-2 mb-1">Jawaban :</h6>
                            <div class="row opsi-${indexQuestion}">
                                <div class="col-12 pl-3 option-${indexQuestion} opsi-${indexQuestion}-0">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <label class="alpha-radio">
                                                        <input type="radio" checked name="answers[${indexQuestion}]" value="A"/>
                                                        <span>A</span>
                                                    </label>
                                                </div>
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="form-group mb-0">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-preview fileinput-exists thumbnail img-thumbnail" style="max-height: 200px; max-width: 100%;"></div>
                                                                <div>
                                                                    <span class="btn btn-raised btn-sm btn-outline-secondary btn-file cursor-pointer">
                                                                        <span class="fileinput-new">
                                                                            <i class="la la-image"></i>
                                                                        </span>
                                                                        <span class="fileinput-exists">
                                                                            <i class="la la-image"></i>
                                                                        </span>
                                                                        <input class="upload-file" type="file" name="image_options[${indexQuestion}][0]" data-param="2" accept="image/*">
                                                                    </span>
                                                                    <input type="hidden" name="delete_image" value="">
                                                                    <a href="#" class="btn btn-raised btn-sm btn-danger fileinput-exists delete_image" data-dismiss="fileinput">
                                                                        <i class="la la-trash"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="ml-1">
                                                            <textarea cols="100" name="options[${indexQuestion}][0]" class="form-control" placeholder="Jawaban"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <button type="button" data-index="${indexQuestion}" class="btn btn-glow btn-warning round btn-raised add-option">
                                        <i class="la la-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;
  $(".questions").append(content);
  indexQuestion++;
});

$("body").on("click", ".delete-question", function () {
  let index = $(this).data("index");
  $(".question-" + index).remove();
  indexQuestion--;
});
$("body").on("click", ".delete-option", function () {
  let index = $(this).data("index");
  let indexOption = $(this).data("length");
  $(`.opsi-${index}-${indexOption}`).remove();
});

function numToAlpha(num) {
  let alpha = "";
  for (; num >= 0; num = parseInt((num / 26).toString(), 10) - 1) {
    alpha = String.fromCharCode((num % 26) + 0x41) + alpha;
  }
  return alpha;
}

$(".save").on("click", function () {
  startLoading();
  $("#form-question").ajaxSubmit({
    success: function (data) {
      stopLoading();
      responseForm(data);
    },
  });
});
