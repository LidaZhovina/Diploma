$(() => {
  $("#registerform-rule").on("click", function () {
    if ($(this).prop("checked")) {
      $(".btn-register").removeClass("disabled");
    } else {
      $(".btn-register").addClass("disabled");
    }
  });
});