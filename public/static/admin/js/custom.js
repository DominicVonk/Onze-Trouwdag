$(document).ready(function () {
  $("[data-auto-size]").each(function () {
    $(this).css("height", (this.scrollHeight + 2) + "px");
  });
  $("[data-auto-size]").on("input", function (e, b) {
    $(this).css("height", (this.scrollHeight + 2) + "px");
  });
  $(document).on("change", ".btn-file :file", function () {
    var input = $(this),
      label = input.val().replace(/\\/g, "/").replace(/.*\//, "");
    input.trigger("fileselect", [label]);
  });

  $(".btn-file :file").on("fileselect", function (event, label) {
    var input = $(this).parents(".input-group").find(":text"),
      log = label;

    if (input.length) {
      input.val(log);
    } else {
      if (log) alert(log);
    }
  });
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $(input).parents(".input-group").find("#img-upload").attr(
          "src",
          e.target.result,
        );
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#imgInp").change(function () {
    readURL(this);
  });
});
