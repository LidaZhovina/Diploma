$(() => {
  $("#registerform-rule").on("click", function () {
    if ($(this).prop("checked")) {
      $(".btn-auth").removeClass("disabled");
    } else {
      $(".btn-auth").addClass("disabled");
    }
  });
});