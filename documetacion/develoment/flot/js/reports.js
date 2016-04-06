$(document).ready(function() {

  plot_charging(data);

});

function plot_charging(data) {
  var placeholder = $("#placeholder");
  var percent;
  placeholder.unbind();
  $.plot(placeholder, data, {
    series: {
      pie: {
        show: true,
        radius: 1,
        innerRadius: 0.5,
        label: {
          show: true,
          radius: 1,
          formatter: labelFormatter,
          background: {
            opacity: 0.5,
            color: '#000'
          }}
      }
    },
    grid: {
      hoverable: true,
      clickable: true
    }
  });

  placeholder.bind("plothover", function(event, pos, obj) {
    if (!obj) {
      return;
    }

    percent = parseFloat(obj.series.percent).toFixed(2);
    $("#hover").html("<span class='text-info' style='font-weight:bold; color:" + obj.series.color + "'>" + obj.series.label + " (" + percent + "%)</span>");
  });

  placeholder.bind("plotclick", function(event, pos, obj) {
    if (!obj) {
      return;
    }

    percent = parseFloat(obj.series.percent).toFixed(2);
    alert("" + obj.series.label + ": " + percent + "%");
  });

}
function labelFormatter(label, series) {
  return "<div style='font-size:10px; text-align:center; padding-right:4px; color:white;'>" + label + " " + series.data[0][1] + "<br/>" + Math.round(series.percent) + "%</div>";
}
function setCode(lines) {
  $("#code").text(lines.join("\n"));
}



