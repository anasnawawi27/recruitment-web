var loadingButtonText =
  '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...';

(function ($) {
  "use strict";
  $(function () {
    var body = $("body");
    var mainWrapper = $(".main-wrapper");
    var footer = $("footer");
    var sidebar = $(".sidebar");
    var navbar = $(".navbar").not(".top-navbar");

    // Enable feather-icons with SVG markup
    feather.replace();

    // initializing bootstrap tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // initialize clipboard plugin
    if ($(".btn-clipboard").length) {
      var clipboard = new ClipboardJS(".btn-clipboard");

      // Enabling tooltip to all clipboard buttons
      $(".btn-clipboard")
        .attr("data-toggle", "tooltip")
        .attr("title", "Copy to clipboard");

      // initializing bootstrap tooltip
      $('[data-toggle="tooltip"]').tooltip();

      // initially hide btn-clipboard and show after copy
      clipboard.on("success", function (e) {
        e.trigger.classList.value = "btn btn-clipboard btn-current";
        $(".btn-current").tooltip("hide");
        e.trigger.dataset.originalTitle = "Copied";
        $(".btn-current").tooltip("show");
        setTimeout(function () {
          $(".btn-current").tooltip("hide");
          e.trigger.dataset.originalTitle = "Copy to clipboard";
          e.trigger.classList.value = "btn btn-clipboard";
        }, 1000);
        e.clearSelection();
      });
    }

    // Applying perfect-scrollbar
    if ($(".sidebar .sidebar-body").length) {
      const sidebarBodyScroll = new PerfectScrollbar(".sidebar-body");
    }
    if ($(".content-nav-wrapper").length) {
      const contentNavWrapper = new PerfectScrollbar(".content-nav-wrapper");
    }
    // if ($('#content-table .content-body').length) {
    //     const tableContentBodyScroll = new PerfectScrollbar('#content-table .content-body');
    // }

    // Sidebar toggle to sidebar-folded
    $(".sidebar-toggler").on("click", function (e) {
      e.preventDefault();
      $(".sidebar-header .sidebar-toggler").toggleClass("active not-active");
      if (window.matchMedia("(min-width: 992px)").matches) {
        e.preventDefault();
        body.toggleClass("sidebar-folded");
      } else if (window.matchMedia("(max-width: 991px)").matches) {
        e.preventDefault();
        body.toggleClass("sidebar-open");
      }
    });

    // Settings sidebar toggle
    $(".settings-sidebar-toggler").on("click", function (e) {
      $("body").toggleClass("settings-open");
    });

    // Sidebar theme settings
    $("input:radio[name=sidebarThemeSettings]").click(function () {
      $("body").removeClass("sidebar-light sidebar-dark");
      $("body").addClass($(this).val());
    });

    // sidebar-folded on large devices
    function iconSidebar(e) {
      if (e.matches) {
        body.addClass("sidebar-folded");
      } else {
        body.removeClass("sidebar-folded");
      }
    }

    var desktopMedium = window.matchMedia(
      "(min-width:992px) and (max-width: 1199px)"
    );
    desktopMedium.addListener(iconSidebar);
    iconSidebar(desktopMedium);

    if ($(".nav-link.active").length) {
      const $navLinkActive = $(".nav-link.active");
      if ($navLinkActive.parents(".sub-menu").length) {
        $navLinkActive.closest(".collapse").addClass("show");
        $navLinkActive.addClass("active");
      }
    }

    //  open sidebar-folded when hover
    // $(".sidebar .sidebar-body").hover(
    //     function () {
    //         if (body.hasClass('sidebar-folded')) {
    //             body.addClass("open-sidebar-folded");
    //         }
    //     },
    //     function () {
    //         if (body.hasClass('sidebar-folded')) {
    //             body.removeClass("open-sidebar-folded");
    //         }
    //     });

    // close sidebar when click outside on mobile/table
    $(document).on("click touchstart", function (e) {
      e.stopPropagation();

      // closing of sidebar menu when clicking outside of it
      if (!$(e.target).closest(".sidebar-toggler").length) {
        var sidebar = $(e.target).closest(".sidebar").length;
        var sidebarBody = $(e.target).closest(".sidebar-body").length;
        if (!sidebar && !sidebarBody) {
          if ($("body").hasClass("sidebar-open")) {
            $("body").removeClass("sidebar-open");
          }
        }
      }
    });

    // initializing popover
    $('[data-toggle="popover"]').popover();

    //checkbox and radios
    $(".form-check label,.form-radio label").append(
      '<i class="input-frame"></i>'
    );

    //Horizontal menu in mobile
    $('[data-toggle="horizontal-menu-toggle"]').on("click", function () {
      $(".horizontal-menu .bottom-navbar").toggleClass("header-toggled");
    });
    // Horizontal menu navigation in mobile menu on click
    var navItemClicked = $(".horizontal-menu .page-navigation >.nav-item");
    navItemClicked.on("click", function (event) {
      if (window.matchMedia("(max-width: 991px)").matches) {
        if (!$(this).hasClass("show-submenu")) {
          navItemClicked.removeClass("show-submenu");
        }
        $(this).toggleClass("show-submenu");
      }
    });

    $(window).scroll(function () {
      if (window.matchMedia("(min-width: 992px)").matches) {
        var header = $(".horizontal-menu");
        if ($(window).scrollTop() >= 60) {
          $(header).addClass("fixed-on-scroll");
        } else {
          $(header).removeClass("fixed-on-scroll");
        }
      }
    });

    // Prevent body scrolling while sidebar scroll
    $(".sidebar .sidebar-body").hover(
      function () {
        $("body").addClass("overflow-hidden");
      },
      function () {
        $("body").removeClass("overflow-hidden");
      }
    );
  });
})(jQuery);

$(".logout").on("click", function () {
  Swal.fire({
    icon: "question",
    text: "You sure want to logout?",
    showCancelButton: true,
    cancelButtonText: "No, cancel",
    confirmButtonText: "Yes, logout",
  })
    .then(function (d) {
      if (d.isConfirmed) {
        window.location.href = siteUrl + "/logout";
      }
    })
    .catch(swal.noop);
});

function responseForm(response) {
  response = JSON.parse(response);
  if (response.redirect) {
    window.location = response.redirect;
  } else {
    if (response.message) {
      if (response.status == "success") {
        successMessage(response.message);
      } else if (response.status == "error") {
        errorMessage(response.message);
      }
    }
  }
}

function successMessage(message) {
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 6000,
  });
  Toast.fire({
    icon: "success",
    title: message,
  });
}

function errorMessage(message = "") {
  Swal.fire({
    icon: "error",
    title: "Oops...",
    html: message ? message : "Something went wrong!",
  });
}

function startLoading() {
  $.blockUI({
    message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
    overlayCSS: {
      backgroundColor: "#FFF",
      opacity: 0.8,
      cursor: "wait",
    },
    css: {
      border: 0,
      padding: 0,
      backgroundColor: "transparent",
    },
  });
}

function stopLoading() {
  $("body").unblock();
}

function stopLoadingWithError() {
  $("body").unblock();
  swal({
    title: "Oops...",
    text: "Something went wrong! Call your administrator.",
    icon: "error",
  });
}

var btnHtmlTemp = "";

function startLoadingButton(el) {
  $(el).attr("disabled", "disabled");
  btnHtmlTemp = $(el).html();
  $(el).html(
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...'
  );
}

function stopLoadingButton(el) {
  $(el).removeAttr("disabled");
  $(el).html(btnHtmlTemp);
}

function deleteIt(url, callback) {
  Swal.fire({
    icon: "warning",
    title: "Are you sure?",
    confirmButtonText: "Yes, delete!",
    showCancelButton: true,
    cancelButtonText: "No, cancel",
  }).then(function (d) {
    if (d.isConfirmed) {
      startLoading();
      $.ajax({
        url: url,
        success: function (res) {
          stopLoading();
          callback(res);
        },
      });
    }
  });
}
