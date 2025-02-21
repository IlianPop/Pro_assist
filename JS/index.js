function validate(form){
  let e = 0;
  if(form.login.value==""){
    form.login.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    e++;
  }
  else{
    form.login.style.border = "none";
  }
  if(form.pass.value==""){
    form.pass.style.borderBottom = "2px solid rgba(219, 109, 13, 0.7)";
    e++;
  }
  else{
    form.pass.style.border = "none";
  }
  if(e==0){
    return(true);
  }
  else{
    return(false);
  }
}
