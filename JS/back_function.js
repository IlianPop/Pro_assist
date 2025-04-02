function handle_back(){
  window.history.replaceState(null, null, document.referrer);
}
