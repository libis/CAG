Timeline.PlanningUnit={};Timeline.PlanningUnit.DAY=0;Timeline.PlanningUnit.WEEK=1;Timeline.PlanningUnit.MONTH=2;Timeline.PlanningUnit.QUARTER=3;Timeline.PlanningUnit.YEAR=4;Timeline.PlanningUnit.getParser=function(){return Timeline.PlanningUnit.parseFromObject};Timeline.PlanningUnit.createLabeller=function(a){return new Timeline.PlanningLabeller(a)};Timeline.PlanningUnit.makeDefaultValue=function(){return 0};Timeline.PlanningUnit.cloneValue=function(a){return a};
Timeline.PlanningUnit.parseFromObject=function(a){if(a==null)return null;else if(typeof a=="number")return a;else try{return parseInt(a)}catch(b){return null}};Timeline.PlanningUnit.toNumber=function(a){return a};Timeline.PlanningUnit.fromNumber=function(a){return a};Timeline.PlanningUnit.compare=function(a,b){return a-b};Timeline.PlanningUnit.earlier=function(a,b){return Timeline.PlanningUnit.compare(a,b)<0?a:b};
Timeline.PlanningUnit.later=function(a,b){return Timeline.PlanningUnit.compare(a,b)>0?a:b};Timeline.PlanningUnit.change=function(a,b){return a+b};
