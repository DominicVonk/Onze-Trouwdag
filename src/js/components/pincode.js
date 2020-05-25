var noFocus = false;

document.querySelectorAll(".pincode input").forEach(function(input) {
  firstEmpty().focus();

  input.onfocus = function() {
    return (
      !noFocus &&
      (firstEmpty().focus(),
      (firstEmpty().selectionStart = firstEmpty().value.length),
      (firstEmpty().selectionEnd = firstEmpty().value.length))
    );
  };

  input.addEventListener("keydown", function(e) {
    // this.value = '';
    if (this.value != "" && e.keyCode != 8) {
      if (this.nextElementSibling) {
        noFocus = true;
        this.nextElementSibling.focus();
        this.nextElementSibling.selectionStart = this.nextElementSibling.value.length;
        this.nextElementSibling.selectionEnd = this.nextElementSibling.value.length;
        noFocus = false;
      }
    } else if (this.value == "" && e.keyCode == 8) {
      noFocus = true;
      this.previousElementSibling.focus();
      this.previousElementSibling.selectionStart = this.previousElementSibling.value.length;
      this.previousElementSibling.selectionEnd = this.previousElementSibling.value.length;
      noFocus = false;
    }
  });
  input.addEventListener("mouseup", function() {
    this.selectionStart = this.value.length;
    this.selectionEnd = this.value.length;
  });
  input.addEventListener("touchend", function() {
    this.selectionStart = this.value.length;
    this.selectionEnd = this.value.length;
  });
  input.addEventListener("touchcancel", function() {
    this.selectionStart = this.value.length;
    this.selectionEnd = this.value.length;
  });
  input.addEventListener("selectionchange", function() {
    this.selectionStart = this.value.length;
    this.selectionEnd = this.value.length;
  });
  input.addEventListener("keyup", async function() {
    if (!this.value.match(/[0-9]/)) {
      this.value = "";
    }

    if (this.value != "") {
      if (this.nextElementSibling) {
        noFocus = true;
        this.nextElementSibling.focus();
        this.nextElementSibling.selectionStart = this.nextElementSibling.value.length;
        this.nextElementSibling.selectionEnd = this.nextElementSibling.value.length;
        noFocus = false;
      } else {
        this.selectionStart = this.value.length;
        this.selectionEnd = this.value.length;
      }
    }

    if (getCode().length === 4) {
      var result = await fetch("/api/code", {
        method: "post",
        body: JSON.stringify({ code: getCode() }),
        credentials: "same-origin"
      });
      result = await result.json();
      if (result.status === 200) {
        location.href = "/main";
        this.blur();
      } else {
        document.querySelector(".pincode").classList.add("wrong"),
          setTimeout(function() {
            document
              .querySelectorAll(".pincode input")
              .forEach(function(input) {
                input.value = "";
              }),
              document.querySelector(".pincode input").focus(),
              document.querySelector(".pincode").classList.remove("wrong");
          }, 820);
      }
    }
  });
});

function firstEmpty() {
  var inp = null;
  document.querySelectorAll(".pincode input").forEach(function(input) {
    if (!inp && input.value == "") {
      inp = input;
    }
  });

  if (!inp) {
    inp = document.querySelectorAll(".pincode input")[
      document.querySelectorAll(".pincode input").length - 1
    ];
  }

  return inp;
}

function getCode() {
  var code = "";
  document.querySelectorAll(".pincode input").forEach(function(input) {
    return (code += input.value);
  });
  return code;
}
