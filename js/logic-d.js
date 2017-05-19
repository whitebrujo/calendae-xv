/*jslint white: true, continue: true, eqeq: true, plusplus: true, unparam: true, sloppy: true, vars: true*/

var curr_month = (new Date()).getMonth() + 1;

$(document).ready(function ()   {
    
    
    // 1 - build calendar
    $('#calendar-days').html(buildCalendar(curr_month));
    $('#month-name').html(localeMonthName(curr_month));
    
    $('#prev-month').click(function ()  {
       
        curr_month = curr_month > 1 ? curr_month - 1 : 12;
        $('#calendar-days').html(buildCalendar(curr_month));
        $('#month-name').html(localeMonthName(curr_month));
        
    });
    
    $('#next-month').click(function ()  {
       
        curr_month = curr_month == 12 ? 1 : curr_month + 1;
        $('#calendar-days').html(buildCalendar(curr_month));
        $('#month-name').html(localeMonthName(curr_month));
        
    });
    
    
  
    $('#logo').click(function ()    {
    
        document.location.href = '';
        
    });
    
    
    $('#choose-button').click(function ()    {
        
        $('#calendar').slideToggle(200);
        
    });
    
    $('.summary').click(function () {
        
        $(this).next('.details').slideToggle(100);
        
    });
  
});

function localeMonthName(month) {
 
    var m = ['', 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
    
    return m < 1 || m > 12 ? null : m[month];
    
}

function buildCalendar(month)  {
    
    var mdays = [0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    var cday = (new Date()).getDate(), cmon = (new Date()).getMonth() + 1;
    var row, col, d = 1;
    var ch = '';
    
    for(row = 1; row <= 5; row++)  {
        ch += '<tr>';
        for(col = 1; col <= 7; col++)   {
            if(d > mdays[month]) break;
            ch += "<td><a href=\"date/" + month.toString() + "/" + d.toString() +  "\">" + d.toString() + '</a></td>';
            d++;
        }
        ch += '</tr>';
    }

    // done.
    return ch;
}
