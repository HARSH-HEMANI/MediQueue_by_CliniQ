$(document).ready(function () {
  function validateInput(input) {
    var field = $(input);
    var value = field.val().trim();
    var name = field.attr("name");
    var errorfield = $("#" + name + "_error");

    var validationType = field.data("validation") || "";
    var minLength = field.data("min") || 0;
    var maxLength = field.data("max") || 9999;
    var fileSize = field.data("filesize") || 0;
    var fileType = field.data("filetype") || "";

    let errorMessage = "";

    if (!validationType) return;

    const validations = validationType.split("|");

    for (let rule of validations) {
      if (rule === "required" && value === "") {
        errorMessage = "This field is required.";
        break;
      }

      if (rule === "min" && value.length < minLength) {
        errorMessage = `Minimum ${minLength} characters required.`;
        break;
      }

      if (rule === "max" && value.length > maxLength) {
        errorMessage = `Maximum ${maxLength} characters allowed.`;
        break;
      }

      if (rule === "email") {
        const emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w]{2,4}$/;
        if (value !== "" && !emailRegex.test(value)) {
          errorMessage = "Enter a valid email address.";
          break;
        }
      }

      if (rule === "number") {
        const numberRegex = /^[0-9]+$/;
        if (value !== "" && !numberRegex.test(value)) {
          errorMessage = "Only numeric values allowed.";
          break;
        }
      }

      if (rule === "strongPassword") {
        const passwordRegex =
          /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{6,}$/;
        if (value !== "" && !passwordRegex.test(value)) {
          errorMessage =
            "Password must contain 6+ chars, uppercase, lowercase, number & special character.";
          break;
        }
      }

      if (rule === "confirmPassword") {
        const original = $("#" + field.data("match")).val();
        if (value !== original) {
          errorMessage = "Passwords do not match.";
          break;
        }
      }

      if (rule === "select" && value === "") {
        errorMessage = "Please select an option.";
        break;
      }

      if (rule === "fileSize") {
        const file = field[0].files[0];
        if (file && file.size > fileSize * 1024) {
          errorMessage = `File must be less than ${fileSize}KB.`;
          break;
        }
      }

      if (rule === "fileType") {
        const file = field[0].files[0];
        if (file) {
          const extension = file.name.split(".").pop().toLowerCase();
          const allowed = fileType
            .split(",")
            .map((ext) => ext.trim().toLowerCase());
          if (!allowed.includes(extension)) {
            errorMessage = `Allowed file types: ${fileType}`;
            break;
          }
        }
      }
    }

    if (errorMessage) {
      errorfield.text(errorMessage).show();
      field.addClass("is-invalid").removeClass("is-valid");
      errorfield.addClass("small text-danger");
    } else {
      errorfield.text("").hide();
      field.removeClass("is-invalid").addClass("is-valid");
    }
  }

  $("input, textarea, select").on("input change", function () {
    validateInput(this);
  });

  $("form").on("submit", function (e) {
    let isValid = true;

    $(this)
      .find("input, textarea, select")
      .each(function () {
        validateInput(this);
        let errorfield = $("#" + $(this).attr("name") + "_error");

        if (errorfield.text().trim() !== "") {
          isValid = false;
        }
      });

    if (!isValid) {
      e.preventDefault();
    }
  });
});
