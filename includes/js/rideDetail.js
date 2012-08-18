var rideDetail = {
    rideID: $('#rideID').text(),
    url: '/wordpress/wp-content/data/rideData.json.php',
    content: $('#leftContent'),

    init: function() {
        rideDetail.getRideData();
        rideDetail.content.find('.rideInfo').accordion();
    },

    getRideData: function() {
        $.ajax({
            type: 'get',
            dataType: 'json',
            url: rideDetail.url,
            data: 'id=' + rideDetail.rideID,
            success: function(data) {
                rideDetail.distance = data['distance'];
                rideDetail.elevation = data['elevation'];
                rideDetail.rideName = data['rideName'];

                rideDetail.createProfile();
            }
        });
    },

    createProfile: function() {
        Highcharts.setOptions(rideDetail.theme);
        rideDetail.elevationProfile = new Highcharts.Chart(rideDetail.chartOptions());
    },

    tickInterval: function() {
        return Math.floor(rideDetail.elevation.length / 12);
    },

    pointToDistance: function(point) {
        var val = (point / rideDetail.elevation.length) * rideDetail.distance;

        return val.toFixed(1);
    },

    pointInterval: function() {
        return Math.ceil((rideDetail.elevation.length / 1000) * rideDetail.smoothingMultiplier);
    },

    chartOptions: function() {
        return {
            credits: {
                enabled: false
            },
            chart: {
                renderTo: 'elevationProfile',
                defaultSeriesType: 'area'
            },
            title: {
                text: ''
            },
            xAxis: {
                title: {
                    text: 'Distance (miles)'
                },
                tickInterval : rideDetail.tickInterval(),
                labels: {
                    formatter: function() {
                        var val = (this.value / rideDetail.elevation.length) * rideDetail.distance;
                        return val.toFixed(1);
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'Elevation (feet)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            plotOptions: {
                area: {
                    marker: {
                        enabled: false
                    },
                    shadow: false
                }
            },
            tooltip: {
                formatter: function() {
                        return '<strong>'+ rideDetail.rideName +'</strong><br />Distance: <strong>'+ rideDetail.pointToDistance(this.x) +' miles</strong><br />Elevation: <strong> '+ rideDetail.commify(this.y) + '\'</strong><br />Grade: <strong> ' + this.point.name + '%</strong>';
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: 'Brownsboro Loop',
                data: rideDetail.elevation
            }]
        }
    },

    theme: {
       colors: ["#FFCC00", "#7798BF", "#55BF3B", "#DF5353", "#aaeeee", "#ff0066", "#eeaaee",
          "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
       chart: {
          backgroundColor: {
             linearGradient: [0, 0, 0, 400],
             stops: [
                [0, 'rgb(96, 96, 96)'],
                [1, 'rgb(16, 16, 16)']
             ]
          },
          borderWidth: 0,
          borderRadius: 0,
          plotBackgroundColor: null,
          plotShadow: false,
          plotBorderWidth: 0
       },
       title: {
          style: {
             color: '#FFF',
             font: '16px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
          }
       },
       subtitle: {
          style: {
             color: '#DDD',
             font: '12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
          }
       },
       xAxis: {
          gridLineWidth: 0,
          lineColor: '#999',
          tickColor: '#999',
          labels: {
             style: {
                color: '#999',
                fontWeight: 'bold'
             }
          },
          title: {
             style: {
                color: '#AAA',
                font: 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
             }
          }
       },
       yAxis: {
          alternateGridColor: null,
          minorTickInterval: null,
          gridLineColor: 'rgba(255, 255, 255, .1)',
          lineWidth: 0,
          tickWidth: 0,
          labels: {
             style: {
                color: '#999',
                fontWeight: 'bold'
             }
          },
          title: {
             style: {
                color: '#AAA',
                font: 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
             }
          }
       },
       legend: {
          itemStyle: {
             color: '#CCC'
          },
          itemHoverStyle: {
             color: '#FFF'
          },
          itemHiddenStyle: {
             color: '#333'
          }
       },
       labels: {
          style: {
             color: '#CCC'
          }
       },
       tooltip: {
          backgroundColor: {
             linearGradient: [0, 0, 0, 50],
             stops: [
                [0, 'rgba(96, 96, 96, .8)'],
                [1, 'rgba(16, 16, 16, .8)']
             ]
          },
          borderWidth: 0,
          style: {
             color: '#FFF'
          }
       },


       plotOptions: {
          line: {
             dataLabels: {
                color: '#CCC'
             },
             marker: {
                lineColor: '#333'
             }
          },
          spline: {
             marker: {
                lineColor: '#333'
             }
          },
          scatter: {
             marker: {
                lineColor: '#333'
             }
          },
          candlestick: {
             lineColor: 'white'
          }
       },

       toolbar: {
          itemStyle: {
             color: '#CCC'
          }
       },

       navigation: {
          buttonOptions: {
             backgroundColor: {
                linearGradient: [0, 0, 0, 20],
                stops: [
                   [0.4, '#606060'],
                   [0.6, '#333333']
                ]
             },
             borderColor: '#000000',
             symbolStroke: '#C0C0C0',
             hoverSymbolStroke: '#FFFFFF'
          }
       },

       exporting: {
          buttons: {
             exportButton: {
                symbolFill: '#55BE3B'
             },
             printButton: {
                symbolFill: '#7797BE'
             }
          }
       },

       // scroll charts
       rangeSelector: {
          buttonTheme: {
             fill: {
                linearGradient: [0, 0, 0, 20],
                stops: [
                   [0.4, '#888'],
                   [0.6, '#555']
                ]
             },
             stroke: '#000000',
             style: {
                color: '#CCC',
                fontWeight: 'bold'
             },
             states: {
                hover: {
                   fill: {
                      linearGradient: [0, 0, 0, 20],
                      stops: [
                         [0.4, '#BBB'],
                         [0.6, '#888']
                      ]
                   },
                   stroke: '#000000',
                   style: {
                      color: 'white'
                   }
                },
                select: {
                   fill: {
                      linearGradient: [0, 0, 0, 20],
                      stops: [
                         [0.1, '#000'],
                         [0.3, '#333']
                      ]
                   },
                   stroke: '#000000',
                   style: {
                      color: 'yellow'
                   }
                }
             }
          },
          inputStyle: {
             backgroundColor: '#333',
             color: 'silver'
          },
          labelStyle: {
             color: 'silver'
          }
       },

       navigator: {
          handles: {
             backgroundColor: '#666',
             borderColor: '#AAA'
          },
          outlineColor: '#CCC',
          maskFill: 'rgba(16, 16, 16, 0.5)',
          series: {
             color: '#7798BF',
             lineColor: '#A6C7ED'
          }
       },

       scrollbar: {
          barBackgroundColor: {
                linearGradient: [0, 0, 0, 20],
                stops: [
                   [0.4, '#888'],
                   [0.6, '#555']
                ]
             },
          barBorderColor: '#CCC',
          buttonArrowColor: '#CCC',
          buttonBackgroundColor: {
                linearGradient: [0, 0, 0, 20],
                stops: [
                   [0.4, '#888'],
                   [0.6, '#555']
                ]
             },
          buttonBorderColor: '#CCC',
          rifleColor: '#FFF',
          trackBackgroundColor: {
             linearGradient: [0, 0, 0, 10],
             stops: [
                [0, '#000'],
                [1, '#333']
             ]
          },
          trackBorderColor: '#666'
       }
    },

    commify: function (str) {
        str += '';
        var x = str.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;

        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
};

rideDetail.init();