var Tv = Tv || {};

var Powertrack = (function(){
    
    var self = this;
    
    self.injectTemplate = function(e){
        e.preventDefault();
        
        var clone = Tv.template.clone();
        
        $('#companies').append(clone);
    };

    return self;
    
})();

$("#add-company").click(Powertrack.injectTemplate);

$(function(){
   Tv.template = $(".ce9af:last-of-type"); 
});