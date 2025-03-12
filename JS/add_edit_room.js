function validate(form){
  let err = 0;
  if(form.title.value === ""){
    form.title.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    err++;
  }
  else{
    form.title.style.border = "none";
  }
  if(isNaN(Number(form.number.value))|| Number(form.number.value) == 0 || Number(form.number.value) > 50){
    form.number.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    err++;
  }
  else{
    form.number.style.border = "none";
  }
  if(err == 0){
    return(true);
  }
  else{
    return(false);
  }
}
