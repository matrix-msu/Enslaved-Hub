$(document).ready(function(){

  $(document).click(function () {
      $('span.sort-projects-text').find("img:first").removeClass('show');
      $('span.sort-projects-text').next().removeClass('show');
      $('.sort-pages p').find("img:first").removeClass('show');
      $('.sort-pages p').next().removeClass('show');
      $('h2.column-header').find("img:first").removeClass('show');
      $('h2.column-header').next().removeClass('show');
  });

  $("h2.column-header").click(function (e) {
      e.stopPropagation();
      $(this).find("img:first").toggleClass('show');
      $(this).next().toggleClass('show');
  });

  $("#both").click(function (e) {
      e.stopPropagation();
      $("h2.column-header").html('Projects & Scholars<img class="sort-arrow" src="./assets/images/directory_chevron.svg" alt="sort projects button"/>');
      $('h2.column-header').find("img:first").removeClass('show');
      $('h2.column-header').next().removeClass('show');
      $(".card.directory.project").show();
      $(".card.directory.scholar").show();
  });

  $("#project").click(function (e) {
      e.stopPropagation();
      $("h2.column-header").html('Projects<img class="sort-arrow" src="./assets/images/directory_chevron.svg" alt="sort projects button"/>');
      $('h2.column-header').find("img:first").removeClass('show');
      $('h2.column-header').next().removeClass('show');
      $(".card.directory.project").show();
      $(".card.directory.scholar").hide();
  });

  $("#scholar").click(function (e) {
      e.stopPropagation();
      $("h2.column-header").html('Scholars<img class="sort-arrow" src="./assets/images/directory_chevron.svg" alt="sort projects button"/>');
      $('h2.column-header').find("img:first").removeClass('show');
      $('h2.column-header').next().removeClass('show');
      $(".card.directory.project").hide();
      $(".card.directory.scholar").show();
  });

  $("span.sort-projects-text").click(function (e) {
      e.stopPropagation();
      $(this).find("img:first").toggleClass('show');
      $(this).next().toggleClass('show');
  });

  $(".sort-pages p").click(function (e) { // toggle show/hide per-page submenu
      e.stopPropagation();
      $(this).find("img:first").toggleClass('show');
      $(this).next().toggleClass('show');
  });

});
