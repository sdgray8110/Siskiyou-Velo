function pop(url, width, height){
  popup = window.open(url, 'popup', 'width=' + width + ',height=' + height + ',scrollbars=no' + ',resizable=no' + ',location=no');
  popup.focus();
  return;
}