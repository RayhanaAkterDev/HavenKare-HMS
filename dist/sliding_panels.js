/*  JavaScript Document                      */
/*  Written by JuhwanPark with a reference of a tutorial at Lynda.com by Chris Converse  */

var panelWidth = 0;

$(document).ready(function(){
    //positino the panel
    window.panelWidth = $('.slidingTab').width();
    $('.slidingTab .panel_container .panels .panel').each(function(index){
        $(this).css({
            'width':window.panelWidth+'px',
            'left':(window.panelWidth*index)
        });
        $('.panels').css({
            'width':(window.panelWidth*(index+1))+'px'
        });
    });
	
	//add click events to tabs
	$('.slidingTab .tab span').click(function(){
	    changePanels($(this).index());
	});

    //set the initial panel
    $('.tab span:first-child').trigger('click');

});

//function to change panel position
function changePanels(newIndex) {
    var newPanelPosition = (window.panelWidth*newIndex)*-1;
    var newPanelHeight = $('.panel:nth-child('+(newIndex+1)+')').height();
    
    $('.slidingTab .tab span').removeClass('selected');
    $('.slidingTab .tab span:nth-child('+(newIndex+1)+')').addClass('selected');
    
    $('.panels').animate({left:newPanelPosition},1000);
    $('.panel_container').animate({height:newPanelHeight},1000);
    
}
