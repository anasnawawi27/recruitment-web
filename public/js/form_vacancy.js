$(".syarat-jurusan").on("click", function () {
  let value = $(this).val();

  if (value == "jurusan_spesifik") {
    $(".list-jurusan").removeClass("d-none");
  } else {
    $(".list-jurusan").addClass("d-none");
  }
});

$(".minimum-nilai").on("click", function () {
  let value = $(this).val();

  if (value == "ya") {
    $(".nilai").removeClass("d-none");
  } else {
    $(".nilai").addClass("d-none");
  }
});
$(".kriteria").on("click", function () {
  let value = $(this).val();
  if (value == "Berpengalaman") {
    $(".berpengalaman").removeClass("d-none");
  } else {
    $(".berpengalaman").addClass("d-none");
  }
});

$("body").on("click", ".switchery", function () {
  if ($(".set-interview").is(":checked")) {
    $(".interview").removeClass("d-none");
  } else {
    $(".interview").addClass("d-none");
  }
});

$(".tag").tagsinput();

let elem = $(".switchery");
let switchery = new Switchery(elem);
