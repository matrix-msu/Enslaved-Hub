$().ready(function(){
  function initializeTimeline() {
      // Timeline points and arrows
      var $tlContainer = $('.timeline-container');
      var $tlInfoContainer = $('.timeline-info-container');
      var $tlInfoContainerArrowBottom = $tlInfoContainer.find('.arrow-pointer-bottom');
      var $tlInfoContainerArrowtop = $tlInfoContainer.find('.arrow-pointer-top');
      var $eventPoints = $('.event-point');
      var $sourceSelect = $('.source-select');
      var $nextButton = $tlContainer.find('.timeline-next');
      var $prevButton = $tlContainer.find('.timeline-prev');
    
      var currentIndex = 0;
      var currentKid = $($eventPoints[0]).data('kid');
      var eventsCount = $eventPoints.length;
    
      //Set info arrow to point at current point
      //var pointLeft = Math.round($($eventPoints[0]).offset().left - $tlInfoContainer.offset().left) + 5;
      //$tlInfoContainerArrowBottom.css('left', pointLeft);
      //$tlInfoContainerArrowtop.css('left', pointLeft);
    
      setTimelineTitle(currentKid);
      

      //Move container to correct position
      $tlContainer.appendTo(".timeline-section");
    
      // Set event title positioning on point hover
      $eventPoints.find('.event-title').each(function() {
        var $title = $(this);
        var left = (-1 * $title.outerWidth() / 2) + 5;
        $title.css('left', left);
      });
    
      $sourceSelect.click(function() {
        setSourceByKid(currentKid);
      });
    
      // Clicking an event point
      $eventPoints.click(function() {
        var $point = $(this);
        currentIndex = $point.data('index');
        currentKid = $point.data('kid');
    
        setEventByKid(currentKid, $point);
      });
    
      // Clicking next button to go to next event
      $nextButton.click(function() {
        currentIndex += 1;
        if (currentIndex >= eventsCount) {
          currentIndex = 0;
        }
    
        var $point = $($eventPoints[currentIndex]);
        currentKid = $point.data('kid');
    
        setEventByKid(currentKid, $point);
      });
    
      // Clicking previous button to go previous event
      $prevButton.click(function() {
        currentIndex -= 1;
        if (currentIndex < 0) {
          currentIndex = eventsCount - 1;
        }
    
        var $point = $($eventPoints[currentIndex]);
        currentKid = $point.data('kid');
    
        setEventByKid(currentKid, $point);
      });
    
      // Info Select
      var $infoSelect = $('.info-select');
      var $info = $('.infowrap');
    
      // Switch between Event and Place Info for an Event on the timeline
      $infoSelect.click(function() {
        var $selected = $(this);
        var type = $selected.data('select');
    
        // Active clicked selector
        $infoSelect.removeClass('active');
        $selected.addClass('active');
    
        // Activate associated info
        $info.removeClass('active');
        $('.'+type+'-info-'+currentKid).addClass('active');
      });
    
      function setEventByKid(kid, $point) {
        // Set point on timeline
        $eventPoints.removeClass('active');
        $point.addClass('active');
    
        // Show corresponding info
        $info.removeClass('active');
        $('.event-info-'+kid).addClass('active');
    
        // Activate event tab
        $infoSelect.removeClass('active');
        $('.info-select-event').addClass('active');
    
        // Set info arrow to point at current point
        var pointLeft = Math.round($point.offset().left - $tlInfoContainer.offset().left) + 5;
        $tlInfoContainerArrowBottom.css('left', pointLeft);
        $tlInfoContainerArrowtop.css('left', pointLeft);
    
        setTimelineTitle(kid);
      }
    
      function setTimelineTitle(kid) {
        $('.info-select-place').show();
    
        var event = $('.event-info-'+currentKid).data('event');
        $('.info-select-event').children('.large-text').text(event);
    
        var place = $('.place-info-'+currentKid).data('place');
    
        if (place) {
          $('.info-select-place').children('.large-text').text(place);
        } else {
          $('.info-select-place').hide();
        }
      }
    
      function setSourceByKid(kid) {
        $sourceSelect.removeClass('active');
        $('source-info-' + kid).addClass('active');
      }

      //Removes the timeline container if there is only 1 event
      if (eventsCount <= 1){
        $tlContainer.remove();
      }
      //Set the arrow to the first event, or no event if there is only 1
      setEventByKid(currentKid, $($eventPoints[0]));

    }
    
    initializeTimeline();
});
