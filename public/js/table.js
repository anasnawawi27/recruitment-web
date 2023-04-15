const $table = $("#table");

$('.caret').addClass('d-none');

function actionFormatterDefault(value, row, index) {
  let $action = "";
  if (row.edit) {
    let url = row.edit;

    if(!row.meal_allowance){
      url = url.replace("ID", row.id);
    } else {
      url = url.replace("ID", row.job_title_id);
    }
    $action +=
      '<a href="' +
      url +
      '" class="btn btn-icon btn-sm btn-warning" style="margin-right:7px"><i class="la la-edit"></i></a>';
  }
  if (row.delete) {
    $action +=
      '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-danger delete"><i class="la la-trash"></i></a>';
  }
  return [$action].join("");

}

function actionFormatterPayroll(value, row, index) {
  let $action = "";
  if (row.pdf) {
    let url = row.pdf;

    url = url.replace("ID", row.id);
    $action += '<a href="' + url + '" target="_blank" class="btn btn-icon btn-sm btn-danger" style="margin-right:7px"><i class="la la-file-text-o"></i></a>';
  }
  if (row.send) {
    $action += '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-info send" style="margin-right:7px"><i class="la la-send-o"></i></a>';
  }
  return [$action].join("");

}

function actionApprovalFormatterDefault(value, row, index) {
  console.log(row)
  let $action = "";
  if (row.approve) {
    let url = row.approve;
    url = url.replace("ID", row.id);
    $action +=
      '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-success approve approval-option" style="margin-right:7px"><i class="la la-check-square-o"></i></a>';
  }
  if (row.cancel) {
    $action +=
      '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-danger cancel approval-option"><i class="la la-times-circle-o"></i></a>';
  }
  return [$action].join("");

}

function currencyFormatterDefault(value, row, index, field) {
  var numberFormatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  });
  let currency = numberFormatter.format(value);
  return [currency].join('');
}

function imageFormatterDefault(value, row, index) {
  let $image = "";
  if (row.image_url) {
    $image = '<img src="' + row.image_url + '" class="thumb-sm"/>';
  }
  return [$image].join("");
}

function detailFormatterDefault(value, row, index, field) {
  let $detail = "";
  if (row.detail) {
    let url = row.detail;
    url = url.replace("ID", row.id);
    $detail += '<a href="' + url + '">' + row[field] + "</a>";
  } else {
    $detail = field;
  }
  return [$detail].join("");
}

function htmlEntitiesDecodeFormatter(value, row, index, field) {
  let $html = "";
  if (value) {
    $html = decodeHTMLEntities(value);
  }
  return [$html].join("");
}

function statusFormatterDefault(value, row, index, field) {
  let $status = "";
  if (value) {
    $status +=
      '<span class="badge badge-pill status-label ' +
      value.replace(" ", "-") +
      '">' +
      value.toUpperCase() +
      "</span>";
  }
  return [$status].join("");
}

function statusFormatterBoolean(value, row, index, field) {
  let $status = "";
  value = value == "1" ? "Active" : "Not Active";
  if (value) {
    $status += '<span class="badge badge-success">' + value + "</span>";
  }
  return [$status].join("");
}


function dateFormatterDefault(value, row, index, field) {
  let date = moment(value);
  return [date.format("MMM, DD YY")].join("");
}

function hourFormatterDefault(value, row, index, field) {
  let val = value > 1 ? value + ' Hours' : value + ' Hour'
  return [val].join("");
}

function percentFormatterDefault(value, row, index, field) {
  let val = value + ' %';
  return [val].join("");
}

function longDateFormatterDefault(value, row, index, field) {
  let date = moment(value);
  return [date.format("DD, MMM YYYY")].join("");
}

function documentFormatterDefault(value, row, index, field) {
  let html = value ? '<span class="text-success text-uppercase">Complete</span>' : '<span class="text-danger text-uppercase">Not Complete</span>';
  return [html].join("");
}

function dateTimeFormatterDefault(value, row, index, field) {
  let date = moment(value);
  return [date.format("MMM, DD HH:mm")].join("");
}

function uppercaseFormatterDefault(value, row, index, field) {
  let val = value.toUpperCase();
  return [val].join("");
}

var actionEventDefault = {
  "click .delete": function (e, value, row, index) {
    let url = row.delete;

    if(!row.meal_allowance){
      url = url.replace("ID", row.id);
    } else {
      url = url.replace("ID", row.job_title_id);
    }

    const swalConfirm = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger mr-1",
      },
      buttonsStyling: false,
    });

    swalConfirm
      .fire({
        title: "Are you sure want to delete?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.value) {
          $.ajax({
            url: url,
            success: function (res) {
              res = JSON.parse(res);
              if (res.status === "success") {
                $table.bootstrapTable("refresh");
                successMessage(res.message);
              } else {
                errorMessage(res.message);
              }
              if ("refresh" in res) {
                $(res.refresh).bootstrapTable("refresh");
              }
            },
          });
        }
      });
  },
};

var actionEventPayroll = {
  "click .send": function (e, value, row, index) {
    let url = row.send;
    url = url.replace("ID", row.id);

    const swalConfirm = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger mr-1",
      },
      buttonsStyling: false,
    });

    swalConfirm
      .fire({
        title: "You Sure Send This Payslip ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, continue!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.value) {
          $.ajax({
            url: url,
            success: function (res) {
              res = JSON.parse(res);
              if (res.status === "success") {
                successMessage(res.message);
              } else {
                errorMessage(res.message);
              }
            },
          });
        }
      });
  },
};

var actionApprovalEventDefault = {
  "click .approval-option": function (e, value, row, index) {
    let isCancel = $(e.currentTarget).hasClass('cancel');
    let type = isCancel ? 'cancel' : 'approve';
    let url = isCancel ? row.cancel : row.approve;
    url = url.replace("ID", row.id);

    const swalConfirm = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger mr-1",
      },
      buttonsStyling: false,
    });

    swalConfirm
      .fire({
        title: "Are you sure want to "+ type +" this request?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, continue !",
        cancelButtonText: "No, cancel!",
        reverseButtons: true,
      })
      .then((result) => {
        if (result.value) {
          $.ajax({
            url: url,
            type: 'post',
            data: {
              type: type,
            },
            success: function (res) {
              res = JSON.parse(res);
              if (res.status === "success") {
                $table.bootstrapTable("refresh");
                successMessage(res.message);
              } else {
                errorMessage(res.message);
              }
              if ("refresh" in res) {
                $(res.refresh).bootstrapTable("refresh");
              }
            },
          });
        }
      });
  },
};

function queryParams(params) {
  let filter = {};
  if ($(".table-filter").length) {
    $(".table-filter").each(function (i, e) {
      filter[$(e).attr("name")] = $(e).val() || "";
    });
  }
  params.filter = filter;
  return params;
}

var tableIcons = {
  paginationSwitchDown: "fa-caret-square-down",
  paginationSwitchUp: "fa-caret-square-up",
  refresh: "fa-sync",
  toggleOff: "fa-toggle-off",
  toggleOn: "fa-toggle-on",
  columns: "mdi-view-module",
  fullscreen: "fa-arrows-alt",
  detailOpen: "fa-plus",
  detailClose: "fa-minus",
  clearSearch: "mdi-filter-remove",
};

var tableButtonsOrder = ["columns"];

function tableButtons() {
  return {
    btnFilter: {
      icon: "mdi-filter",
      event: function () {
        $("#modal-table-filter").modal("show");
      },
      attributes: {
        title: "Filter",
      },
    },
    btnFilterClear: {
      icon: "mdi-filter-remove",
      event: function () {
        $("#modal-table-filter .table-filter").val("");
        $("#table").bootstrapTable("refresh");
        $(".bootstrap-table .columns button[name=btnFilterClear]").hide();
      },
      attributes: {
        title: "Clear Filter",
      },
    },
  };
}

$("#btn-submit-table-filter").click(function () {
  $("#table").bootstrapTable("refresh");
  let filtered = false;
  $("#modal-table-filter .table-filter").each(function (i, e) {
    if ($(e).val()) {
      filtered = true;
      return false;
    }
  });
  if (!filtered) {
    $(".bootstrap-table .columns button[name=btnFilterClear]").hide();
  } else {
    $(".bootstrap-table .columns button[name=btnFilterClear]").show();
  }
  $("#modal-table-filter").modal("hide");
});

function initTable() {
  $table.bootstrapTable("destroy").bootstrapTable();
}

function decodeHTMLEntities(str) {
  const entities = [
    ["amp", "&"],
    ["apos", "'"],
    ["#x27", "'"],
    ["#x2F", "/"],
    ["#39", "'"],
    ["#47", "/"],
    ["lt", "<"],
    ["gt", ">"],
    ["nbsp", " "],
    ["quot", '"'],
  ];

  for (let i = 0, max = entities.length; i < max; ++i) {
    str = str.replace(
      new RegExp("&" + entities[i][0] + ";", "g"),
      entities[i][1]
    );
  }
  return str;
}

$(function () {
  if ($table.length > 0) {
    initTable();
  }
});