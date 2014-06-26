var NeatlineTime = {
  resizeTimerID: null,

  resizeTimeline: function() {
     if (resizeTimerID == null) {
        resizeTimerID = window.setTimeout(function() {
            resizeTimerID = null;
            tl.layout();
        }, 500);
    }
  },

  _monkeyPatchFillInfoBubble: function() {
      var oldFillInfoBubble =
          Timeline.DefaultEventSource.Event.prototype.fillInfoBubble;
      Timeline.DefaultEventSource.Event.prototype.fillInfoBubble =
          function(elmt, theme, labeller) {
          var doc = elmt.ownerDocument;

          var title = this.getText();
          var link = this.getLink();
          var image = this.getImage();

          if (image != null) {
              var img = doc.createElement("img");
              img.src = image;

              theme.event.bubble.imageStyler(img);
              elmt.appendChild(img);
          }

          var divTitle = doc.createElement("div");
          var textTitle = doc.createElement("span");
          textTitle.innerHTML = title;
          if (link != null) {
              var a = doc.createElement("a");
              a.href = link;
              a.appendChild(textTitle);
              divTitle.appendChild(a);
          } else {
              divTitle.appendChild(textTitle);
          }
          theme.event.bubble.titleStyler(divTitle);
          elmt.appendChild(divTitle);

          var divBody = doc.createElement("div");
          this.fillDescription(divBody);
          theme.event.bubble.bodyStyler(divBody);
          elmt.appendChild(divBody);

          var divTime = doc.createElement("div");
          this.fillTime(divTime, labeller);
          theme.event.bubble.timeStyler(divTime);
          elmt.appendChild(divTime);

          var divWiki = doc.createElement("div");
          this.fillWikiInfo(divWiki);
          theme.event.bubble.wikiStyler(divWiki);
          elmt.appendChild(divWiki);
      };
  },

  loadTimeline: function(timelineId, timelineData) {
    NeatlineTime._monkeyPatchFillInfoBubble();
    var eventSource = new Timeline.DefaultEventSource();

    var defaultTheme = Timeline.getDefaultTheme();
    defaultTheme.mouseWheel = 'zoom';

    var bandInfos = [
        Timeline.createBandInfo({
            eventSource: eventSource,
            width: "80%",
            intervalUnit: Timeline.DateTime.YEAR,
            intervalPixels: 100,
            zoomIndex: 2,
            zoomSteps: new Array(
              {pixelsPerInterval: 100, unit: Timeline.DateTime.DAY},
              {pixelsPerInterval: 100, unit: Timeline.DateTime.MONTH},
              {pixelsPerInterval: 100, unit: Timeline.DateTime.YEAR} // DEFAULT zoomIndex
            )
        }),
        Timeline.createBandInfo({
            overview: true,
            eventSource: eventSource,
            width: "20%",
            intervalUnit: Timeline.DateTime.DECADE,
            intervalPixels: 200
        })
    ];

    bandInfos[1].syncWith = 0;
    bandInfos[1].highlight = true;
    tl = Timeline.create(document.getElementById(timelineId), bandInfos);
     
    tl.getBand(1).addOnScrollListener(function(timeline){
        tl.loadJSON(
            event_source_url(
                url,
                tl.getBand(0).getMinDate(),
                tl.getBand(0).getMaxDate(),
                function(json,url){eventSource.loadJSON(json,url)}
            )
        )
    });


   
    /*tl.loadJSON(timelineData, function(json, url) {
        if (json.events.length > 0) {
            eventSource.loadJSON(json, url);
            tl.getBand(0).setCenterVisibleDate(eventSource.getEarliestDate());
        }
    });*/

  }
};