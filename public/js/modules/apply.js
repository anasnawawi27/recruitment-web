$(".experience").on("change", function () {
  if ($(this).val() == "yes") {
    $(".experience-year").removeClass("d-none");
  } else {
    $(".experience-year").addClass("d-none");
  }
});

$(".major").on("click", function () {
  if ($(this).val() == "other") {
    $(".jurusan-lain").removeClass("d-none");
  } else {
    $(".jurusan-lain").addClass("d-none");
  }
});

$(document).ready(function () {
  let validate = configValidate;
  validate.rules = {
    last_education: "required",
    jurusan: "required",
    nilai_terakhir: "required",
    berpengalaman: "required",
  };
  validate.messages = {
    last_education: "Pendidikan Terakhir Wajib Diisi",
    jurusan: "Jurusan Wajib Diisi",
    nilai_terakhir: "Nilai Terakhir Wajib Diisi",
    berpengalaman: "Kriteria Wajib Diisi",
  };
  validate.submitHandler = function (form) {
    const $submit = $(form).find("button[type=submit]");
    $submit.attr("disabled", "disabled");
    $submit.html(loadingButtonText);
    $(form).ajaxSubmit({
      success: function (res) {
        responseForm(res);
      },
    });
    return false;
  };
  $("#apply").validate(validate);
});
