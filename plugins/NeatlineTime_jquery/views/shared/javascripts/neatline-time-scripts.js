var NeatlineTime = {
  
  /*_monkeyPatchFillInfoBubble: function() {
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
  },*/

  loadTimeline: function(timelineId, timelineData) {
    
    //NeatlineTime._monkeyPatchFillInfoBubble();
    var eventSource = new Timeline.DefaultEventSource(0),
    theme = Timeline.ClassicTheme.create(),
    d = Timeline.DateTime.parseGregorianDateTime("1870");
    
    theme.event.bubble.width = 320;
    theme.event.bubble.height = 220;
    theme.ether.backgroundColors[1] = theme.ether.backgroundColors[0];
    //alert("\"#"+timelineId+"\"");
    jQuery.get(timelineData, function (json) {
        
        jQuery("#"+timelineId).syrinxTimeline({
            bands: [
                {   
                    width: "80%",
                    intervalUnit: Timeline.DateTime.YEAR,
                    intervalPixels: 100,
                    theme: theme,
                    zoomIndex: 2,
                    zoomSteps: new Array(
                      {pixelsPerInterval: 100, unit: Timeline.DateTime.DAY},
                      {pixelsPerInterval: 100, unit: Timeline.DateTime.MONTH},
                      {pixelsPerInterval: 100, unit: Timeline.DateTime.YEAR} // DEFAULT zoomIndex
                    )
                },{                    
                    overview: true,                   
                    width: "20%",
                    theme: theme,
                    intervalUnit: Timeline.DateTime.DECADE,
                    intervalPixels: 200                   
                }
            ]
        });    
    
        eventSource.loadJSON(json,"");
  
    },"json");
  }};    