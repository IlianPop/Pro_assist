function names(e) {
  return !/^[А-ЯЇЄІҐ][а-яїєіїґ']*[а-яїєіїґ]$/.test(e);
}
function validate(form){
  let err = 0;
  if(form.title.value === ""){
    form.title.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    err++;
  }
  else{
    form.title.style.border = "none";
  }
  if(names(form.name.value)){
    form.name.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    err++;
  }
  else{
    form.name.style.border = "none";
  }
  if(names(form.last_name.value)){
    form.last_name.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    err++;
  }
  else{
    form.last_name.style.border = "none";
  }
  if(names(form.midle_name.value)){
    form.midle_name.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    err++;
  }
  else{
    form.midle_name.style.border = "none";
  }
  if(form.login.value == ""){
    form.login.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    err++;
  }
  else{
    form.login.style.border = "none";
  }
  if(!/^[A-Za-z0-9\-\_\.]+@gmail.com$/.test(form.mail.value)){
    form.mail.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    err++;
  }
  else{
    form.mail.style.border = "none";
  }
  if(!form.changes || form.pass.value != ""){
    if(!/[A-Z]/.test(form.pass.value) || !/[a-z]/.test(form.pass.value) || !/[0-1]/.test(form.pass.value)){
      form.pass.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
      err++;
    }
    else{
      form.pass.style.border = "none";
    }
    if(!form.pass.value == form.pass_repeat.value || form.pass_repeat.value == ""){
      form.pass_repeat.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
      err++;
    }
    else{
      form.pass_repeat.style.border = "none";
    }
  }
  if(err == 0){
    return(true);
  }
  else{
    return(false);
  }
}
