$(document).ready(function () {
  let validate = configValidate;
  validate.rules = {
    file_vaksin_1: "required",
    pas_photo: "required",
    cv: "required",
  };
  validate.messages = {
    file_vaksin_1: "File vaksin wajib diisi",
    pas_photo: "Pass Fhoto Wajib Diisi",
    cv: "CV Wajib Diisi",
  };
  validate.submitHandler = function (form) {
    const $submit = $(form).find("button[type=submit]");
    $submit.attr("disabled", "disabled");
    $submit.html(loadingButtonText);
    $(form).ajaxSubmit({
      success: function (res) {
        res = JSON.parse(res);
        if (res.status === "success") {
          window.location = res.redirect;
        } else {
          $submit.removeAttr("disabled");
          $submit.html("Login");
          $("#message").html(res.message);
        }
      },
    });
    return false;
  };
  $("#complete-data").validate(validate);
});

// var pdfjsLib = window["pdfjs-dist/build/pdf"];
// // The workerSrc property shall be specified.
// pdfjsLib.GlobalWorkerOptions.workerSrc =
//   "https://mozilla.github.io/pdf.js/build/pdf.worker.js";

$(".upload-file").on("change", function (e) {
  let param = $(this).data("param");
  var file = e.target.files[0];

  if (file.type == "application/pdf") {
    var fileReader = new FileReader();
    fileReader.onload = function () {
      var pdfData = new Uint8Array(this.result);

      var loadingTask = pdfjsLib.getDocument({ data: pdfData });
      loadingTask.promise.then(
        function (pdf) {
          console.log("PDF loaded");

          // Fetch the first page
          var pageNumber = 1;
          pdf.getPage(pageNumber).then(function (page) {
            console.log("Page loaded");

            var scale = 1.5;
            var viewport = page.getViewport({ scale: scale });

            var canvas = $("#preview-" + param)[0];
            var context = canvas.getContext("2d");

            canvas.height = viewport.height;
            canvas.width = viewport.width;
            canvas.style.removeProperty("height");
            canvas.style.width = "70%";
            canvas.classList.add("img-thumbnail");

            // Render PDF page into canvas context
            var renderContext = {
              canvasContext: context,
              viewport: viewport,
            };
            var renderTask = page.render(renderContext);
            renderTask.promise.then(function () {
              console.log("Page rendered");
            });
          });
        },
        function (reason) {
          // PDF loading error
          console.error(reason);
        }
      );
    };
    fileReader.readAsArrayBuffer(file);
  }
});
