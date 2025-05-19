function validate(form) {
  let err = 0;
  if (form.title.value === "") {
    form.title.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    err++;
  } else {
    form.title.style.border = "none";
  }
  if (form.type.value == "") {
    form.type.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    err++;
  } else {
    form.type.style.border = "none";
  }
  if (err == 0) {
    return true;
  } else {
    return false;
  }
}
