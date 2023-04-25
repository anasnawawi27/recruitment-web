const configValidate = {
  ignore:
    "input[type=hidden], .select2-search__field, .note-editable.card-block",
  errorElement: "label",
  errorClass: "invalid-feedback",
  highlight: function (element, errorClass) {
    $(element)
      .closest(".form-group")
      .removeClass("is-invalid")
      .addClass("is-invalid");
    $(element).addClass("is-invalid");
  },
  unhighlight: function (element, errorClass) {
    $(element).removeClass("is-invalid");
    $(element).closest(".form-group").removeClass("is-invalid");
  },
  errorPlacement: function (label, element) {
    if (element.parent(".input-group").length) {
      label.insertAfter(element.parent());
    } else if (element.hasClass("select2-hidden-accessible")) {
      label.insertAfter(element.next("span"));
    } else if (element.next("small").length > 0) {
      label.insertAfter(element.next());
    } else {
      label.insertAfter(element);
    }
  },
  showErrors: function (errorMap, errorList) {
    this.defaultShowErrors();
    if (errorList.length > 0) {
      const $tabPane = $(errorList[0].element).closest(".tab-pane");
      if ($tabPane.length > 0) {
        const tabID = $tabPane.attr("id");
        $('a[href="#' + tabID + '"]').trigger("click");
      }
    }
  },
  success: function (label) {
    label.closest(".form-group").removeClass("is-invalid");
    label.remove();
  },
  submitHandler: function (form) {
    if ($(form).hasClass("no-ajax")) {
      if ($(form).hasClass("target-blank")) {
        $(form).attr("target", "javascript:window.open('','targetNew')");
      }
      $(form)[0].submit();
    } else {
      startLoading();
      $(form).ajaxSubmit({
        success: function (data) {
          stopLoading();
          responseForm(data);
        },
      });
      return false;
    }
  },
};
const configSelect2Ajax = {
  ajax: {
    url: "",
    type: "post",
    dataType: "json",
    delay: 250,
    cache: false,
    data: function (params) {
      return {
        q: params.term,
        page: params.page || 1,
      };
    },
    processResults: function (data, params) {
      return data;
    },
  },
  language: {
    searching: function () {
      return '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
    },
  },
  escapeMarkup: function (markup) {
    return markup;
  },
  tags: false,
};
const configMultiSelect = {
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  maxHeight: 200,
  includeSelectAllOption: true,
  buttonWidth: "100%",
  buttonClass: "custom-select text-left",
};
$(document).ready(function () {
  $(".select2").select2({
    width: "100%",
  });

  //number
  const inputNumber = $(".number");
  if (inputNumber.length > 0) {
    inputNumber.number(true);
    inputNumber.attr("autocomplete", "off");
  }

  const inputDatePicker = $(".input-date");
  if (inputDatePicker.length > 0) {
    inputDatePicker.each(function (i, el) {
      let input = $(el)
        .datepicker({
          language: "en",
          autoClose: true,
          dateFormat: dateFormatPicker,
        })
        .data("datepicker");
      if ($(el).data("value") !== "") {
        input.selectDate(new Date($(el).data("value")));
      }
    });
  }

  const $formSelect2 = $(".form-select2");
  if ($formSelect2.length > 0) {
    $formSelect2.select2();
    $formSelect2.on("select2:close", function (e) {
      $(this).valid();
    });
  }

  const inputDeleteImage = $(".delete_image");
  if (inputDeleteImage.length > 0) {
    inputDeleteImage.click(function () {
      let param = $(this).data("param");
      $(this).parent().find(`input[name=${param}]`).val(1);
    });
  }
  const $form = $("#form");
  if ($form.length > 0) {
    $form.validate(configValidate);
  }

  const inputEditor = $(".editor");
  if (inputEditor.length > 0) {
    inputEditor.summernote({
      height: 320,
      minHeight: null,
      maxHeight: null,
      focus: true,
      callbacks: {
        onFocus: function () {
          console.log("Editable area is focused");
        },
      },
    });
  }

  const inputMultiSelect = $(".multiselect");
  if (inputMultiSelect.length > 0) {
    inputMultiSelect.multiselect(configMultiSelect);
  }
});
