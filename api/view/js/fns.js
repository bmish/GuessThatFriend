$(document).ready(function() {
  $(".apiCommandSectionTable").hide();
  //toggle the componenet with class msg_body
  $(".apiCommandSectionTitle").click(function()
  {
    $(this).next(".apiCommandSectionTable").slideToggle(500);
  });
});