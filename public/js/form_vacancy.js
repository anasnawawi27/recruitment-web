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

$(".is-online").on("click", function () {
  if ($(this).is(":checked")) {
    $(".link").removeClass("d-none");
    $(".lokasi").addClass("d-none");
  } else {
    $(".lokasi").removeClass("d-none");
    $(".link").addClass("d-none");
  }
});

$(".syarat-gender").on("click", function () {
  if ($(this).is(":checked")) {
    $(".gender").removeClass("d-none");
  } else {
    $(".gender").addClass("d-none");
  }
});

$(".syarat-umur").on("click", function () {
  if ($(this).is(":checked")) {
    $(".umur").removeClass("d-none");
  } else {
    $(".umur").addClass("d-none");
  }
});

$(".tag").tagsinput();

let elem = $(".switchery");
let switchery = new Switchery(elem);

$("#kategori-soal").on("change", function () {
  console.log($(this).val());

  $.ajax({
    type: "post",
    dataType: "json",
    data: {
      ids_category: $(this).val(),
    },
    url: siteUrl + "/admin/job-vacancy/get_total_questions",
    success: function (res) {
      total_soal = res.jumlah_soal;
      $("#jumlah-soal-psikotest").html(
        `<small class="text-muted block-area">Jumlah Soal : ${res.jumlah_soal} Soal</small>`
      );
    },
  });
});

$("#nilai-persoal").on("keyup", function () {
  value = $(this).val();

  if (value) {
    point_persoal = value;
    total_nilai = point_persoal * total_soal;
    $("#nilai-minimum").removeAttr("disabled");
    $("#total-nilai").html(
      `<small class="text-muted block-area">Total Nilai : ${total_nilai}. Nilai Minimum tidak boleh melebihi nilai ini. </small>`
    );
  } else {
    point_persoal = 0;
    $("#nilai-minimum").attr("disabled", "disabled");
    $("#total-nilai").html(
      `<small class="text-muted block-area">Total Nilai : 0 </small>`
    );
  }
});

$("#nilai-minimum").on("keyup", function () {
  let value = $(this).val();
  if (value) {
    if (value > total_nilai) {
      $("#total-nilai").html(
        `<small class="block-area text-danger">Nilai Minimum melebihi Total Nilai : ${total_nilai}.</small>`
      );
    } else {
      $("#total-nilai").html(
        `<small class="text-muted block-area">Total Nilai : ${total_nilai}. Nilai Minimum tidak boleh melebihi nilai ini. </small>`
      );
    }
  } else {
    $("#total-nilai").html(
      `<small class="text-muted block-area">Total Nilai : ${total_nilai}. Nilai Minimum tidak boleh melebihi nilai ini. </small>`
    );
  }
});
