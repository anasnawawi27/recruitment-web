$(document).ready(function () {
  let loginValidate = configValidate;
  loginValidate.rules = {
    email: {
      required: true,
      email: true,
    },
    password: "required",
  };
  loginValidate.messages = {
    email: {
      required: lang.email.msg.required,
      email: lang.email.msg.email,
    },
    password: lang.password.msg.required,
  };
  loginValidate.submitHandler = function (form) {
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
  $("#login").validate(loginValidate);

  let registerValidate = configValidate;
  registerValidate.rules = {
    nama_lengkap: "required",
    tempat_lahir: "required",
    tanggal_lahir: "required",
    no_handphone_1: "required",
    email: {
      required: true,
      email: true,
    },
    username: "required",
    password: "required",
    konfirmasi_password: {
      required: true,
      equalTo: "#password",
    },
  };
  registerValidate.messages = {
    nama_lengkap: {
      required: "Nama Lengkap tidak boleh kosong",
    },
    tempat_lahir: {
      required: "Tempat Lahir tidak boleh kosong",
    },
    tanggal_lahir: {
      required: "Tanggal Lahir tidak boleh kosong",
    },
    no_handphone_1: {
      required: "No Handphone 1 tidak boleh kosong",
    },
    email: {
      required: "Email tidak boleh kosong",
      email: "Email Tidak Valid",
    },
    username: {
      required: "Username tidak boleh kosong",
    },
    password: {
      required: "Password tidak boleh kosong",
    },
    konfirmasi_password: {
      required: "Konfirmasi Password tidak boleh kosong",
      equalTo: "Password tidak sama",
    },
  };
  registerValidate.submitHandler = function (form) {
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
  $("#register").validate(registerValidate);
});
