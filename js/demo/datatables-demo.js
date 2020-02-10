	/**
	* Функция обработки ошибок
	* @param json
	* @return bool 
	*/
	function GetError(error) {
		console.log(error);
	}
$.ajaxSetup({ // параметры ajax-запроса по умолчанию 
  dataType: 'json',
    beforeSend: function() {
      $('.loader').show();
    },
    complete: function() {
      $('.loader').hide();
    },
    error: function(jqXHR, textStatus, errorThrown) {
    //  console.log('Ошибка: ' + textStatus + ' | ' + errorThrown);
    $('.loader').hide();
    GetError('Ошибка: '+errorThrown);
    }
});
actions = {
  getSeries: function (res){
    $(".modal-body .card").remove();
    for (let i = 0; i < res.length; i++) {
      $(".modal-body").append(renderSeries(res[i]));
    }
  }
};
function renderSeries(arr){
  let cardStart = '<div class="card">',
      cardHeader = '<div class="card-header">Дата: '+prettyDate(arr.MainDicomTags.SeriesDate)+'</div>',
      cardBodyStart = '<h5 class="card-title">Modality: '+arr.MainDicomTags.Modality+'</h5>',
      bodyPartExamined = '<p class="card-text"><span id="bodKey">BodyPartExamined</span>:<span id="bodVal">'+arr.MainDicomTags.BodyPartExamined+'</span></p>',
      seriesNumber = '<p class="card-text"><span id="serKey">SeriesNumber</span>:<span id="serVal">'+arr.MainDicomTags.SeriesNumber+'</span></p>',
      link = '<a href="http://localhost:8042/osimis-viewer/app/index.html?series='+arr.ID+'" id="seriesButton" class="btn btn-primary">Просмотр</a>',
      cardBodyEnd = "</div>",
      cardEnd = "</div>";
      return cardStart+cardHeader+cardBodyStart+bodyPartExamined+seriesNumber+link+cardBodyEnd+cardEnd;
}
/**
* Универсальная функция обработки ajax асинхронного запроса
*/
function ajaxRequest(url, type, data, responseHandler) {
  $.ajax({
    url:url,
    type:type,
    data: {data:data},
    success: actions[responseHandler]
  });
}
function prettyDate(date){
  return date[6]+date[7]+"-"+date[4]+date[5]+"-"+date[0]+date[1]+date[2]+date[3];
}
function getSeries(ID){
  let data = JSON.stringify(ID);
  console.log(data);
  ajaxRequest("/ajax/getSeries.php","POST",ID,'getSeries');
}
$(document).ready(function() {
  /** 
   * Данные таблицы сортировка+пагинация
   */
  $.fn.dataTable.moment( 'DD-MM-YYYY' );
  $('#dataTable').DataTable();
  /**
   * Локализация выпадающих календарей (datepicker)
   * 
   */
  var options ={
    format: 'DD-MM-YYYY',
    separator: ' | ',
    applyLabel: 'ОК',
    cancelLabel: 'очистить',
    fromLabel: 'От',
    toLabel: 'До',
    customRangeLabel: 'Custom',
    weekLabel: 'Н',
    daysOfWeek: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб','Вс'],
    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    firstDay: 0
  };
  $('input[name="patientBDate"]').daterangepicker({autoUpdateInput: false,locale:options,singleDatePicker: true, showDropdowns: true,});
  $('input[name="studyDate"]').daterangepicker({autoUpdateInput: false,locale:options, showDropdowns: true,});
  $('input[name="studyDate"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD-MM-YYYY') + ' | ' + picker.endDate.format('DD-MM-YYYY'));
  });
  $('input[name="patientBDate"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.endDate.format('DD-MM-YYYY'));
  });
  $('input[name="clear"]').click(function(){
    $('input[name="patientBDate"]').val('');
    $('input[name="studyDate"]').val('');
    $('input[name="iin"]').val('');
    $('input[name="FIO"]').val('');
    window.location.href = window.location.href;
  });

});
$(".series").click(function(){
  var arr = $(this).data('series');
  getSeries(arr);
});